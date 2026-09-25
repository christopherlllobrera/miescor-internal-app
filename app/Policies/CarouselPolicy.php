<?php

namespace App\Policies;

use App\Models\Carousel;
use App\Models\User;

class CarouselPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('View-carousel');
    }

    public function view(User $user, Carousel $carousel)
    {
        return $user->hasPermissionTo('View-carousel');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-carousel');
    }

    public function update(User $user, Carousel $carousel)
    {
        return $user->hasPermissionTo('Update-carousel');
    }

    public function delete(User $user, Carousel $carousel)
    {
        return $user->hasPermissionTo('Delete-carousel');
    }

    public function restore(User $user, Carousel $carousel)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, Carousel $carousel)
    {
        return $user->hasAnyRole('superadmin');
    }
}
