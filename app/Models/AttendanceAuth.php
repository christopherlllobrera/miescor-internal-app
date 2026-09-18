<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $empNo
 * @property string|null $employee_group
 * @property int|null $location_id
 * @property string|null $schedule
 * @property string|null $reason
 * @property string|null $status
 * @property string|null $immediate_supervisor_id
 * @property string|null $next_level_supervisor_id
 * @property string|null $attachment
 * @property string|null $remarks
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Employee|null $employee
 * @property-read Collection<int, AttendanceAuthItem> $items
 * @property-read Employee|null $immediate_supervisor
 * @property-read Employee|null $next_level_supervisor
 */
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
        'attachment',
        'created_by',
        'updated_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

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
