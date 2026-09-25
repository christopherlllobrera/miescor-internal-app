<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkflowTagModule;

class WorkflowTagPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('View-workflow-tag');
    }

    public function view(User $user, WorkflowTagModule $workflowTagModule)
    {
        return $user->hasPermissionTo('View-workflow-tag');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-workflow-tag');
    }

    public function update(User $user, WorkflowTagModule $workflowTagModule)
    {
        return $user->hasPermissionTo('Update-workflow-tag');
    }

    public function delete(User $user, WorkflowTagModule $workflowTagModule)
    {
        return $user->hasPermissionTo('Delete-workflow-tag');
    }

    public function restore(User $user, WorkflowTagModule $workflowTagModule)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, WorkflowTagModule $workflowTagModule)
    {
        return $user->hasAnyRole('superadmin');
    }
}
