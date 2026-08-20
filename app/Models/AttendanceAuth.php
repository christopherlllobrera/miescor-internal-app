<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceAuth extends Model
{
    protected $fillable = [
        'empNo',
        'employee_group',
        'location_id',
        'schedule',
        'reason',
        'status',
        'immediate_supervisor_id',
        'next_level_supervisor_id',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'empNo', 'EmpNo');
    }

    public function items(): HasMany
    {
        return $this->hasMany(AttendanceAuthItem::class, 'attendance_auth_id');
    }

    public function immediate_supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'immediate_supervisor_id', 'EmpNo');
    }

    public function next_level_supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'next_level_supervisor_id', 'EmpNo');
    }
}
