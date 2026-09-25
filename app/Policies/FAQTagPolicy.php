<?php

namespace App\Policies;

use App\Models\FAQTagModule;
use App\Models\User;

class FAQTagPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('View-faq-tag');
    }

    public function view(User $user, FAQTagModule $faqTagModule)
    {
        return $user->hasPermissionTo('View-faq-tag');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Create-faq-tag');
    }

    public function update(User $user, FAQTagModule $faqTagModule)
    {
        return $user->hasPermissionTo('Update-faq-tag');
    }

    public function delete(User $user, FAQTagModule $faqTagModule)
    {
        return $user->hasPermissionTo('Delete-faq-tag');
    }

    public function restore(User $user, FAQTagModule $faqTagModule)
    {
        return $user->hasAnyRole('superadmin');
    }

    public function forceDelete(User $user, FAQTagModule $faqTagModule)
    {
        return $user->hasAnyRole('superadmin');
    }
}
