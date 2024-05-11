<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customer_id',
        'address_id',
        'address',
        'amount',
        'description',
        'status',
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class,'address_id');
    }
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function orderStatusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class);
    }
    public function invoices(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
