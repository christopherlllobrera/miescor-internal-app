<?php

namespace App\Models;

use App\Models\Concerns\HasSelfServiceRequestorFields;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $empNo
 * @property string|null $employee_group
 * @property int|null $location_id
 * @property string|null $business_unit
 * @property string|null $org_unit
 * @property string|null $schedule
 * @property string|null $vl_balance
 * @property string|null $sl_balance
 * @property string|null $type
 * @property Carbon|null $date_start
 * @property Carbon|null $date_end
 * @property float|null $days_total
 * @property string|null $reason
 * @property string|null $attachment
 * @property string|null $duration
 * @property string|null $status
 * @property string|null $immediate_supervisor_id
 * @property string|null $next_level_supervisor_id
 * @property string|null $remarks
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Employee|null $employee
 * @property-read BusinessUnits|null $businessUnit
 * @property-read Location|null $location
 * @property-read Location|null $orgUnitLocation
 * @property-read EmployeeStatus|null $employeeStatus
 * @property-read Employee|null $immediate_supervisor
 * @property-read Employee|null $next_level_supervisor
 */
class LeaveRequest extends Model
{
    use HasSelfServiceRequestorFields;

    protected $fillable = [
        'empNo',
        'employee_group',
        'location_id',
        'schedule',
        'business_unit',
        'org_unit',
        'vl_balance',
        'sl_balance',
        'type',
        'date_start',
        'date_end',
        'days_total',
        'reason',
        'attachment',
        'duration',
        'status',
        'immediate_supervisor_id',
        'next_level_supervisor_id',
        'remarks',
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

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            if ($model->org_unit && ! $model->location_id) {
                $location = Location::where('LocDesc', $model->org_unit)->first();
                if ($location) {
                    $model->location_id = (string) $location->LocNo;
                }
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'empNo', 'EmpNo');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'org_unit', 'LocDesc');
    }

    public function immediate_supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'immediate_supervisor_id', 'EmpNo');
    }

    public function next_level_supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'next_level_supervisor_id', 'EmpNo');
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnits::class, 'business_unit', 'BusinessUnitDesc');
    }

    public function orgUnitLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'org_unit', 'LocDesc');
    }

    public function employeeStatus(): BelongsTo
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_group', 'EmpStatusDesc');
    }
}
