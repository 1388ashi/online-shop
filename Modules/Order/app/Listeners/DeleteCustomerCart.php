<?php

namespace Modules\Order\Listeners;

use Illuminate\Support\Facades\Auth;
use Modules\Cart\Models\Cart;
use Modules\Order\Events\CreateOrders;

class deleteCustomerCart
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
        $carts->delete();  
    }
}
