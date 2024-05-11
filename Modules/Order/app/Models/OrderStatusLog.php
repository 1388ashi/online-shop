<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Database\factories\OrderStatusLogFactory;

class OrderStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
