<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('View-comments');
    }

    public function view(User $user, Comment $comment)
    {
        return $user->hasPermissionTo('View-comments');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-comments');
    }

    public function update(User $user, Comment $comment)
    {
        return $user->hasPermissionTo('Update-comments');
    }

    public function delete(User $user, Comment $comment)
    {
        return $user->hasPermissionTo('Delete-comments');
    }

    public function restore(User $user, Comment $comment)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, Comment $comment)
    {
        return $user->hasAnyRole('superadmin');
    }
}
