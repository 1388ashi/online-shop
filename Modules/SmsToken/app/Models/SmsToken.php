<?php

namespace Modules\SmsToken\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsToken extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'mobile',
        'expires_at',
        'verified_at',
        'token'
    ];
    
}
