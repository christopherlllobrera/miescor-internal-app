<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\DepartmentModule;
use App\Models\Employee;
use App\Models\User;

class DepartmentModulePolicy
{
    /**
     * Internal helper to verify if a user's department matches the record.
     */
    private function isDepartmentAuthorized(User $user, DepartmentModule $departmentModule): bool
    {
        if ($user->hasRole(['superadmin', 'TDE Team'])) {
            return true;
        }

        $employee = Employee::where('EmpNo', $user->empNo)->first();
        if (! $employee || ! $employee->DeptNo) {
            return false;
        }

        if ($user->hasRole('Department PIC')) {
            $dept = Department::where('DeptNo', $employee->DeptNo)
                ->orWhere('CostCntrNo', $employee->DeptNo)
                ->first();

            $costCenter = $dept?->CostCntrNo ?? $employee->DeptNo;
            $deptGroup = strtoupper(substr($costCenter, 0, 4));

            if (! $deptGroup) {
                return false;
            }

            $moduleCostCenter = strtoupper($departmentModule->cms_department_cost_center ?? '');
            $moduleName = strtoupper($departmentModule->cms_department_name ?? '');

            return str_starts_with($moduleCostCenter, $deptGroup) || str_starts_with($moduleName, $deptGroup);
        }

        return false;
    }

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('View-department-page');
    }

    public function view(User $user, DepartmentModule $departmentModule)
    {
        return $user->hasPermissionTo('View-department-page') && $this->isDepartmentAuthorized($user, $departmentModule);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-department-page');
    }

    public function update(User $user, DepartmentModule $departmentModule)
    {
        return $user->hasPermissionTo('Update-department-page') && $this->isDepartmentAuthorized($user, $departmentModule);
    }

    public function delete(User $user, DepartmentModule $departmentModule)
    {
        return $user->hasPermissionTo('Delete-department-page') && $this->isDepartmentAuthorized($user, $departmentModule);
    }

    public function restore(User $user, DepartmentModule $departmentModule)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, DepartmentModule $departmentModule)
    {
        return $user->hasAnyRole('superadmin');
    }
}
