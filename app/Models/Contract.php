<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'created_by',
        'updated_by',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'EmpLName');
    }

    public function contractProponent(): BelongsTo
    {
        return $this->belongsTo(ContractProponent::class, 'proponent', 'proponent_code');
    }
}
