<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Http\Requests\StoreRequest;
use Modules\Cart\Http\Requests\UpdateRequest;
use Modules\Cart\Models\Cart;
use Modules\Product\Models\Product;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $carts = Cart::with('product:id,title,price,quantity,discount,discount_type')
        ->select('id','product_id','price','quantity')
        ->latest('id')
        ->get();

        $notifications = [];
        foreach ($carts as $cart) {
            $title = $cart->product->title;
            $price = $cart->product->price;
            // foreach ($cart->product as $product) {
                if ($cart->product->store->balance < $cart->quantity) {
                    $cart->delete();
                    $notifications[] = "محصول با عنوان $title ناموجود شد";

                }elseif ($cart->product->totalPriceWithDiscount() != $cart->price) {
                    $cart->price = $cart->product->totalPriceWithDiscount();
                    $cart->save();
                    $notifications[] = "قیمت محصول $title به $price تومان تغییر کرده است.";
                }
            // }
        }
        
        return response()->success('', compact('notifications','carts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {

            $product = Product::find($request->product_id);
            $customer = Auth::guard('customer-api')->user();

            $cart = Cart::query()->create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->totalPriceWithDiscount(),
            'customer_id' => $customer->id
            ]);

            return response()->success('سبد خرید با موفقیت به ثبت شد.');
        } catch (\Exception $e) {
            
            return response()->error('خطا در ساخت سبد خرید.');
        }
    }

    public function update(UpdateRequest $request, Cart $cart): JsonResponse
    {
        try {
            $cart->update($request->validated());
            
            return response()->success('سبد خرید با موفقیت به روزرسانی شد.');
        } catch (\Exception $e) {
            
            return response()->error('خطا در به روزرسانی سبد خرید.');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart): JsonResponse
    {
        try {
            $cart->delete();

            return response()->success('سبد خرید با موفقیت حذف شد.');
        } catch (\Exception $e) {

            return response()->error('خطا در حذف سبد خرید.');
        }
    }
}