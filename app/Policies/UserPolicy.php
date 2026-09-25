<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        if ($user->hasPermissionTo('View-User')) {
            return true;
        }

        return false;
    }

    public function view(User $user)
    {
        if ($user->hasPermissionTo('View-User')) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        if ($user->hasPermissionTo('Create-User')) {
            return true;
        }

        return false;
    }

    public function update(User $user)
    {
        if ($user->hasPermissionTo('Update-User')) {
            return true;
        }

        return false;
    }

    public function delete(User $user)
    {
        if ($user->hasPermissionTo('Delete-User')) {
            return true;
        }

        return false;
    }

    public function restore(User $user)
    {
        return $user->hasRole('superadmin');
    }

    public function forceDelete(User $user)
    {
        return $user->hasRole('superadmin');
    }

    public function canImport(User $user): bool
    {
        return $user->hasPermissionTo('import-User');
    }

    public function canExport(User $user): bool
    {
        return $user->hasPermissionTo('export-User');
    }
}
