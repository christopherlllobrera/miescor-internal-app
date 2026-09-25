<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('View-categories');
    }

    public function view(User $user, Category $category)
    {
        return $user->hasPermissionTo('View-categories');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-categories');
    }

    public function update(User $user, Category $category)
    {
        return $user->hasPermissionTo('Update-categories');
    }

    public function delete(User $user, Category $category)
    {
        return $user->hasPermissionTo('Delete-categories');
    }

    public function restore(User $user, Category $category)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, Category $category)
    {
        return $user->hasAnyRole('superadmin');
    }
}
