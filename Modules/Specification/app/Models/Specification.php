<?php

namespace Modules\Specification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Specification extends Model
{
    use LogsActivity,HasFactory;

    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults()
        ->logOnly($this->fillable)
        ->setDescriptionForEvent(fn (string $eventName) => 'مشخصات ' . __('logs.' . $eventName));
    }
    
    protected $fillable = [
        'name',
        'status',
        'category_id',
    ];
    public function categories() : BelongsToMany{
      return $this->belongsToMany(Category::class);
    }
    public function products() : BelongsToMany{
      return $this->belongsToMany(Product::class);
    }
}
