<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'code',
        'type',       // 'percent' or 'fixed'
        'value',
        'expires_at'
    ];

    protected $dates = ['expires_at'];
}
