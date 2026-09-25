<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\DirectoryModule;
use App\Models\Employee;
use App\Models\User;

class DirectoryPolicy
{
    /**
     * Internal helper to verify if a user's department matches the record.
     */
    private function isDepartmentAuthorized(User $user, DirectoryModule $directoryModule): bool
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

            return $directoryModule->department()
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
        return $user->hasPermissionTo('View-directory-page');
    }

    public function view(User $user, DirectoryModule $directoryModule)
    {
        return $user->hasPermissionTo('View-directory-page') && $this->isDepartmentAuthorized($user, $directoryModule);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-directory-page');
    }

    public function update(User $user, DirectoryModule $directoryModule)
    {
        return $user->hasPermissionTo('Update-directory-page') && $this->isDepartmentAuthorized($user, $directoryModule);
    }

    public function delete(User $user, DirectoryModule $directoryModule)
    {
        return $user->hasPermissionTo('Delete-directory-page') && $this->isDepartmentAuthorized($user, $directoryModule);
    }

    public function restore(User $user, DirectoryModule $directoryModule)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, DirectoryModule $directoryModule)
    {
        return $user->hasAnyRole('superadmin');
    }
}
