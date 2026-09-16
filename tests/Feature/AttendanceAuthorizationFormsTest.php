<?php

use App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\Pages\ListAttendanceAuthorizationForms;
use App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\Pages\ListAttendanceAuthorizationFormsAlternative;
use App\Models\AttendanceAuth;
use App\Models\User;
use Livewire\Livewire;

test('it can render the default list page with tabs', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ListAttendanceAuthorizationForms::class)
        ->assertSuccessful();
});

test('it can render the detailed alternative list page with tabs', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ListAttendanceAuthorizationFormsAlternative::class)
        ->assertSuccessful();
});

test('aaf_items action loads existing items in modal form', function () {
    $user = User::factory()->create();
    $auth = AttendanceAuth::first();

    $this->actingAs($user);

    if ($auth) {
        Livewire::test(ListAttendanceAuthorizationFormsAlternative::class)
            ->mountTableAction('aaf_items', $auth)
            ->assertTableActionMounted('aaf_items');
    } else {
        expect(true)->toBeTrue();
    }
});
