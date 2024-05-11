<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\App\Models\BaseModel;
use Modules\Product\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Store extends BaseModel
{
    use LogsActivity,HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'balance',
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->setDescriptionForEvent(fn (string $eventName) => 'انبار ' . __('logs.' . $eventName));
    }
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(StoreTransaction::class,'store_id');
    }
}
