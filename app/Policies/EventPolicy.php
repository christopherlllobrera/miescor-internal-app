<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('View-events');
    }

    public function view(User $user, Event $event)
    {
        return $user->hasPermissionTo('View-events');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-events');
    }

    public function update(User $user, Event $event)
    {
        return $user->hasPermissionTo('Update-events');
    }

    public function delete(User $user, Event $event)
    {
        return $user->hasPermissionTo('Delete-events');
    }

    public function restore(User $user, Event $event)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, Event $event)
    {
        return $user->hasAnyRole('superadmin');
    }
}
