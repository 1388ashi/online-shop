<?php

namespace Modules\Area\Entities;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Core\App\Models\BaseModel;
use Modules\Core\App\Traits\Filterable;
use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;
use Modules\Customer\Models\Address;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class City extends BaseModel
{
    use  Filterable, LogsActivity;

    protected $fillable = [
        'name', 'status'
    ];

    public $sortable = [
        'id', 'province_id', 'name', 'created_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->setDescriptionForEvent(fn(string $eventName) => 'شهر ' . __('logs.' . $eventName));
    }

    public static function clearAllCaches(): void
    {
        if (Cache::has('all_cities')) {
            Cache::forget('all_cities');
        }
    }

    public static function clearCitiesCacheByProvince(int $provinceId): void
    {
        if (Cache::has('cities_' . $provinceId)) {
            Cache::forget('cities_' . $provinceId);
        }
    }

    protected static function booted(): void
    {
        static::created(function () {
            static::clearAllCaches();
        });
        static::updated(function () {
            static::clearAllCaches();
        });
        static::saved(function () {
            static::clearAllCaches();
        });
        static::deleted(function () {
            static::clearAllCaches();
        });
    }

    public static function getAllCities(): \Illuminate\Support\Collection
    {
        return Cache::rememberForever('all_cities', function () {
            return City::query()
                ->where('status', 1)
                ->get(['id', 'name', 'province_id']);
        });
    }

    public function province(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class,'address_id');
    }
}
