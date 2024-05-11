<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\App\Models\BaseModel;

class StoreTransaction extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'store_id',
        'quantity',
        'description',
        'type',
    ];
    public function store() : BelongsTo{
      return $this->belongsTo(Store::class);
    }
}
