<?php

namespace Modules\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Customer\Models\Customer;
use Modules\Product\Models\Product;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'price',
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
