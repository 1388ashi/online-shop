<?php

namespace Modules\Order\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderStatusLog;

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
    public function update(Order $order): JsonResponse
    {
        $order->update([
            'status' => $order->status
        ]);
        OrderStatusLog::query()->create([
            'order_id' => $order->id,
            'status' => $order->status
        ]);   

        return response()->success(compact('order'));
    }
}
