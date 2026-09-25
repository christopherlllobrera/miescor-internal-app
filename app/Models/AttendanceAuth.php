<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $empNo
 * @property string|null $employee_group
 * @property int|null $location_id
 * @property string|null $schedule
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
 * @property-read BusinessUnits|null $businessUnit
 * @property-read Location|null $subAreaLocation
 * @property-read EmployeeStatus|null $employeeStatus
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
        'business_unit',
        'sub_area',
        'schedule',
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

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnits::class, 'business_unit', 'BusinessUnitDesc');
    }

    public function subAreaLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'sub_area', 'LocDesc');
    }

    public function employeeStatus(): BelongsTo
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_group', 'EmpStatusDesc');
    }

    public static function getDefaultBusinessUnit(?string $empNo = null): ?string
    {
        $employee = static::resolveEmployee($empNo);

        return $employee?->department?->businessUnit?->BusinessUnitDesc
            ?? $employee?->businessUnit?->BusinessUnitDesc;
    }

    public static function getDefaultSubArea(?string $empNo = null): ?string
    {
        $employee = static::resolveEmployee($empNo);

        return $employee?->department?->location?->LocDesc;
    }

    public static function getDefaultEmployeeGroup(?string $empNo = null): ?string
    {
        $employee = static::resolveEmployee($empNo);
        $statusDesc = $employee?->employeeStatus?->EmpStatusDesc;

        if (! $statusDesc) {
            return null;
        }

        return match (strtoupper($statusDesc)) {
            'REGULAR' => 'Regular',
            'PROBATIONARY' => 'Probationary',
            'CONTRACTUAL', 'PROJECT-BASED' => 'Project Hire',
            default => ucwords(strtolower($statusDesc)),
        };
    }

    /**
     * @return array<string, string>
     */
    public static function getSubAreaOptions(?string $businessUnitDesc, ?string $currentState = null): array
    {
        if (! $businessUnitDesc) {
            return Location::orderBy('LocDesc')->pluck('LocDesc', 'LocDesc')->toArray();
        }

        $businessUnit = BusinessUnits::where('BusinessUnitDesc', $businessUnitDesc)->first();

        if (! $businessUnit) {
            return $currentState ? [$currentState => $currentState] : [];
        }

        $locNos = Department::where('BUNo', $businessUnit->BusinessUnitNo)
            ->pluck('LocNo')
            ->filter()
            ->unique();

        $locCodes = $locNos->map(fn ($no) => "Code{$no}")->all();

        $options = Location::query()
            ->where(function ($query) use ($locNos, $locCodes) {
                $query->whereIn('LocNo', $locNos)
                    ->orWhereIn('LocCode', $locCodes);
            })
            ->orderBy('LocDesc')
            ->pluck('LocDesc', 'LocDesc')
            ->toArray();

        if ($currentState && ! isset($options[$currentState])) {
            $options[$currentState] = $currentState;
        }

        return $options;
    }

    protected static function resolveEmployee(?string $empNo = null): ?Employee
    {
        $resolvedEmpNo = $empNo ?? Auth::user()?->EmpNo ?? Auth::user()?->empNo;

        if (! $resolvedEmpNo) {
            return null;
        }

        return Auth::user()?->employee ?? Employee::find($resolvedEmpNo);
    }
}
