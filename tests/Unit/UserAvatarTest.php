<?php

use App\Models\Employee;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class);

test('user avatar_url falls back to linked employee ItemPict route', function () {
    $employee = new Employee([
        'EmpNo' => '10030947',
        'ItemPict' => "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x01\x00`\x00`\x00\x00\xFF\xDB\x00C",
    ]);

    $user = new User([
        'empNo' => '10030947',
        'username' => 'Test User',
        'avatar_url' => null,
    ]);
    $user->id = 1;
    $user->setRelation('employee', $employee);

    expect($user->avatar_url)->toBe(route('users.avatar', 1));
    expect($user->getFilamentAvatarUrl())->toBe(route('users.avatar', 1));
});

test('user avatar_url returns null when no avatar and no employee ItemPict', function () {
    $user = new User([
        'empNo' => '10030947',
        'username' => 'Test User',
        'avatar_url' => null,
    ]);
    $user->id = 2;
    $user->setRelation('employee', null);

    expect($user->avatar_url)->toBeNull();
    expect($user->getFilamentAvatarUrl())->toBeNull();
});
