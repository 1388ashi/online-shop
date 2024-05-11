<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;
use Modules\Order\Http\Requests\ParchaseRequest;
use Modules\Order\Models\OrderStatusLog;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice as shetabitInvoice;
use Modules\Invoice\Models\Payment;
use Modules\Order\Events\CreateOrders;
use Shetabit\Payment\Facade\Payment as shetabitPayment;
use Modules\Order\Models\Order;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::query()
        ->latest('id')
        ->get();

        return response()->success('',compact('orders'));
    }
    
    public function show(Order $order): JsonResponse
    {
        return response()->success(compact('order'));
    }

    public function drivers(): JsonResponse
    {
        $allDrivers = Payment::getAllDrivers();
        $drivers = [];
        foreach ($allDrivers as $driver => $options) {
            $drivers[$driver] = [
                'label' => $options['label'],
                'icon' => $options['image'],
            ];
        }

        return response()->success('Get all bank drivers', compact('drivers'));
    }
    public function parchase(ParchaseRequest $request) : JsonResponse 
    {
        // try {
            $address = Address::findOrFail($request->address_id);
            $customer = Customer::findOrFail(auth('customer-api')->user()->id);
            
            $order = Order::query()->create([
                'address_id' => $address->id,
                'address' => $address->toJson(),
                'amount' => $customer->totalPriceForCart(),
                'customer_id' => $customer->id,
                'status' => 'wait_for_payment'
            ]);    

            Event::dispatch(new CreateOrders($order));
            $driver = $request->input('driver_name');
            $route = route('payments.verify',$driver);
            
            $invoice = Invoice::query()->create([
                'order_id' => $order->id,
                'amount' => $order->amount,
                'status' => 0
            ]);    
            $payment = Payment::query()->create([
                'invoice_id' => $invoice->id,
                'amount' => $order->amount,
                'driver' => $driver,
                'status' => 0
            ]);    
            //amount always Toman
            // $response = ShetabitPayment::via($driver)->callbackUrl($route)->purchase(
            //     (new shetabitInvoice)->amount($payment->amount),
            //     function($driver, $transactionId) use ($payment) {
            //         dd('tst');  
            //         $payment->update([
            //             'token' => $transactionId
            //         ]);
            //     }
            //     )->pay()->toJson();
            // $url = json_decode($response)->action;
            $url = url("/payment");

            return response()->success('', compact('url'));
        // } catch (\Exception $exception) {
        //     return response()->error('مشکلی رخ داده است: ' . $exception->getMessage(), 500);
        // }
    }
    public function verify(Request $request, string $driver): Renderable
    {
        $drivers = Payment::getAllDrivers();
        $transactionId = $drivers[$driver]['options']['transaction_id'];

        $message = 'خطای ناشناخته';
        $status = 'error';

        $payment = Payment::query()->where('token', $request->{$transactionId})->first();
        $invoice = Invoice::where('payment_id',$payment->id)->first();
        $order = Order::where('id',$invoice->order_id)->first();
        
        DB::beginTransaction();
        try {

            if (!$payment) {
                throw new InvoiceNotFoundException('پرداختی نامعتبر است!');
            }

            $receipt = ShetabitPayment::via($driver)
                ->amount($payment->amount)
                ->transactionId($payment->token)
                ->verify();
            //Update payment
            $payment->update([
                'tracking_code' => $receipt->getReferenceId(),
                'status' => 1
            ]);

            $invoice->update([
                'status' => 1
            ]);
            $order->update([
                'status' => 'new'
            ]);
            OrderStatusLog::query()->create([
                'order_id' => $order->id,
                'status' => $order->status
            ]);    
            
            //Update order status
            // $order = $payment->order;
            // // $order->status = OrderStatus::STATUS_SUCCESS->value;
            // $order->save();
            
                DB::commit();
                
                $message = 'پرداخت با موفقیت انجام شد.';
                $status = 'success';


        } catch (InvalidPaymentException|InvoiceNotFoundException $exception) {
            DB::rollBack();

            $message = $exception->getMessage();
            //Update payment
            $payment->update([
                'description' => $message
            ]);
            //Update order status
            if ($order->status === 'wait_for_payment') {
                $order->update([
                    'status' => 'failed'
                ]);
                OrderStatusLog::query()->create([
                    'order_id' => $order->id,
                    'status' => 'failed'
                ]);    
            }
        }
        
        return view('order::payment.verify', compact('message', 'status', 'payment'));
    }
}
