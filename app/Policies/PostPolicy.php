<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('View-post');
    }

    public function view(User $user, Post $post)
    {
        return $user->hasPermissionTo('View-post');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-post');
    }

    public function update(User $user, Post $post)
    {
        // Superadmin and TDE Team can update all posts
        if ($user->hasAnyRole(['superadmin', 'TDE Team'])) {
            return $user->hasPermissionTo('Update-post');
        }

        // Marketing Team can update any post created by Marketing Team members
        if ($user->hasRole('Marketing Team')) {
            // Find the user by EmpNo (not by id)
            $postUser = User::where('EmpNo', $post->user_id)->first();

            return $postUser && $postUser->hasRole('Marketing Team') && $user->hasPermissionTo('Update-post');
        }

        return false;
    }

    public function delete(User $user, Post $post)
    {
        return $user->hasPermissionTo('Delete-post');
    }

    public function restore(User $user, Post $post)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, Post $post)
    {
        return $user->hasAnyRole('superadmin');
    }
}
