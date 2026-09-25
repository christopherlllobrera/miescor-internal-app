<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\Employee;
use App\Models\FAQModule;
use App\Models\User;

class FAQPolicy
{
    /**
     * Internal helper to verify if a user's department matches the record.
     */
    private function isDepartmentAuthorized(User $user, FAQModule $faqModule): bool
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

            return $faqModule->department()
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
        return $user->hasPermissionTo('View-faq-page');
    }

    public function view(User $user, FAQModule $faqModule)
    {
        return $user->hasPermissionTo('View-faq-page') && $this->isDepartmentAuthorized($user, $faqModule);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-faq-page');
    }

    public function update(User $user, FAQModule $faqModule): bool
    {
        return $user->hasPermissionTo('Update-faq-page') && $this->isDepartmentAuthorized($user, $faqModule);
    }

    public function delete(User $user, FAQModule $faqModule)
    {
        return $user->hasPermissionTo('Delete-faq-page') && $this->isDepartmentAuthorized($user, $faqModule);
    }

    public function restore(User $user, FAQModule $faqModule)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, FAQModule $faqModule)
    {
        return $user->hasAnyRole('superadmin');
    }
}
