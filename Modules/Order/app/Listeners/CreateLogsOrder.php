<?php

namespace Modules\Order\Listeners;

use Modules\Order\Events\CreateOrders;
use Modules\Order\Models\OrderStatusLog;

class createLogsOrder
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
        
        OrderStatusLog::query()->create([
            'order_id' => $order->id,
            'status' => $order->status
        ]);    
    }
}
