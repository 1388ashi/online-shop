<?php

namespace Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group',
        'label',
        'type',
        'value', 
    ];
    const GROUP = [
        'social' => 'social',
        'general' => 'general',
    ];
}
