<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\Order;
use Spatie\Activitylog\LogOptions;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Customer extends Model implements Authenticatable
{
    use HasFactory, LogsActivity, AuthenticatableTrait, HasApiTokens;

    public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'مشتری ' . __('logs.' . $eventName));
	}
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'mobile',
        'password',
        'email',
        'national_code',
        'mobile_verified_at',
        'status'
    ];
    public function totalPriceForCart(): int
	{
        return $this->carts()->sum('price');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class,'address_id');
    }
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
