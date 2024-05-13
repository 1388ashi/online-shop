<?php

namespace Modules\Order\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Models\Cart;
use Modules\Order\Events\CreateOrders;
use Modules\Order\Models\OrderItem;

class createOrderItems
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CreateOrders $event): void
    {
        $order = $event->order;
        $customerId = Auth::guard('customer-api')->user()->id;
        $carts = Cart::query()->where('customer_id',$customerId)->get();

        $carts->map(function ($cart) use ($order){
            OrderItem::query()->create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'price' => $cart->price,
                'quantity' => $cart->quantity,
                'status' => true
            ]);    
        });
    }
}
