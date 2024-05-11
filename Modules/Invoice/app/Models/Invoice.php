<?php

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Invoice\Database\factories\InvoiceFactory;
use Modules\Order\Models\Order;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'amount',
        'status',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function payments() : HasMany{
		return $this->hasMany(Payment::class);
    }
}
