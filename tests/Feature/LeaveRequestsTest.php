<?php

use App\Filament\Resources\PayrollSelfService\LeaveRequests\Pages\ListLeaveRequests;
use App\Filament\Resources\PayrollSelfService\LeaveRequests\Pages\ListLeaveRequestsAlternative;
use App\Models\User;
use Livewire\Livewire;

test('it can render the default leave requests list page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ListLeaveRequests::class)
        ->assertSuccessful();
});

test('it can render the detailed alternative leave requests list page with tabs', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ListLeaveRequestsAlternative::class)
        ->assertSuccessful();
});
