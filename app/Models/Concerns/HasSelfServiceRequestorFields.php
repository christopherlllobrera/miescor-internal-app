<?php

namespace App\Models\Concerns;

use App\Models\BusinessUnits;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

trait HasSelfServiceRequestorFields
{
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
