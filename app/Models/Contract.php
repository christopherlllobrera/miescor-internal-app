<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'reference_no',
        'contract_description',
        'assigned_to',
        'attachment',
        'contract_type',
        'status',
        'turnaround_days',
        'has_turnaround_time',
        'turnaround_date',
        'deadline',
        'remarks',
        'proponent',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'has_turnaround_time' => 'boolean',
            'turnaround_days' => 'integer',
            'turnaround_date' => 'date',
            'deadline' => 'date',
        ];
    }
}
