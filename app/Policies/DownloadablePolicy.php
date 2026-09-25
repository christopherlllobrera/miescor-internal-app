<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\DownloadableModule;
use App\Models\Employee;
use App\Models\User;

class DownloadablePolicy
{
    /**
     * Internal helper to verify if a user's department matches the record.
     */
    private function isDepartmentAuthorized(User $user, DownloadableModule $downloadableModule): bool
    {
        // 1. Superadmin override
        if ($user->hasRole(['superadmin', 'TDE Team'])) {
            return true;
        }

        // 2. Check Employee Record
        $employee = Employee::where('EmpNo', $user->empNo)->first();
        if (! $employee || ! $employee->DeptNo) {
            return false;
        }

        // 3. Department PIC Logic
        if ($user->hasRole('Department PIC')) {
            $dept = Department::where('DeptNo', $employee->DeptNo)
                ->orWhere('CostCntrNo', $employee->DeptNo)
                ->first();

            $costCenter = $dept?->CostCntrNo ?? $employee->DeptNo;
            $deptGroup = strtoupper(substr($costCenter, 0, 4));

            if (! $deptGroup) {
                return false;
            }

            return $downloadableModule->department()
                ->where(function ($q) use ($deptGroup) {
                    $q->where('cms_department_cost_center', 'like', $deptGroup.'%')
                        ->orWhere('cms_department_name', 'like', $deptGroup.'%');
                })
                ->exists();
        }

        return false;
    }

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('View-downloadables');
    }

    public function view(User $user, DownloadableModule $downloadableModule)
    {
        return $user->hasPermissionTo('View-downloadables') && $this->isDepartmentAuthorized($user, $downloadableModule);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-downloadables');
    }

    public function update(User $user, DownloadableModule $downloadableModule)
    {
        return $user->hasPermissionTo('Update-downloadables') && $this->isDepartmentAuthorized($user, $downloadableModule);
    }

    public function delete(User $user, DownloadableModule $downloadableModule)
    {
        return $user->hasPermissionTo('Delete-downloadables') && $this->isDepartmentAuthorized($user, $downloadableModule);
    }

    public function restore(User $user, DownloadableModule $downloadableModule)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, DownloadableModule $downloadableModule)
    {
        return $user->hasAnyRole('superadmin');
    }
}
