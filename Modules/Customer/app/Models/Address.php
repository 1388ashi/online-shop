<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Area\Entities\City;
use Modules\Order\Models\Order;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Address extends Model
{
    use HasFactory,LogsActivity;


    public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'ادرس ' . __('logs.' . $eventName));
	}
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customer_id',
        'city_id',
        'name',
        'mobile',
        'address',
        'postal_code',
    ];
    
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class,'order_id');
    }
}
