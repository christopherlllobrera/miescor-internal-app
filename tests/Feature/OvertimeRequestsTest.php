<?php

use App\Filament\Resources\PayrollSelfService\OvertimeRequests\Pages\ListOvertimeRequests;
use App\Filament\Resources\PayrollSelfService\OvertimeRequests\Pages\ListOvertimeRequestsAlternative;
use App\Models\User;
use Livewire\Livewire;

test('it can render the default overtime requests list page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ListOvertimeRequests::class)
        ->assertSuccessful();
});

test('it can render the detailed alternative overtime requests list page with tabs', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ListOvertimeRequestsAlternative::class)
        ->assertSuccessful();
});
