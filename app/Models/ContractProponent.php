<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractProponent extends Model
{
    protected $fillable = [
        'proponent_name',
        'proponent_code',
        'business_unit',
        'departments',
    ];

    protected $casts = [
        'business_unit' => 'array',
        'departments' => 'array',
    ];
}
