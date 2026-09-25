<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\WorkflowModule;

class WorkflowPolicy
{
    /**
     * Internal helper to verify if a user's department matches the record.
     */
    private function isDepartmentAuthorized(User $user, WorkflowModule $workflowModule): bool
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

            return $workflowModule->department()
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
        return $user->hasPermissionTo('View-workflow');
    }

    public function view(User $user, WorkflowModule $workflowModule)
    {
        return $user->hasPermissionTo('View-workflow') && $this->isDepartmentAuthorized($user, $workflowModule);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-workflow');
    }

    public function update(User $user, WorkflowModule $workflowModule)
    {
        return $user->hasPermissionTo('Update-workflow') && $this->isDepartmentAuthorized($user, $workflowModule);
    }

    public function delete(User $user, WorkflowModule $workflowModule)
    {
        return $user->hasPermissionTo('Delete-workflow') && $this->isDepartmentAuthorized($user, $workflowModule);
    }

    public function restore(User $user, WorkflowModule $workflowModule)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, WorkflowModule $workflowModule)
    {
        return $user->hasAnyRole('superadmin');
    }
}
