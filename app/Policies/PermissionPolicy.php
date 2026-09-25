<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasRole('superadmin');
    }

    public function view(User $user, Permission $permission)
    {
        return $user->hasRole('superadmin');
    }

    public function create(User $user)
    {
        return $user->hasRole('superadmin');
    }

    public function update(User $user, Permission $permission)
    {
        return $user->hasRole('superadmin');
    }

    public function delete(User $user, Permission $permission)
    {
        return $user->hasRole('superadmin');
    }

    public function restore(User $user, Permission $permission)
    {
        return $user->hasRole('superadmin');
    }

    public function forceDelete(User $user, Permission $permission)
    {
        return $user->hasRole('superadmin');
    }
}
