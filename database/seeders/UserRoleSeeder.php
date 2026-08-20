<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::updateOrCreate(['name' => 'user']);

        $this->command->info('User created successfully!');

        $user = User::updateOrCreate(
            ['comp_email' => 'jclllobrera@miescor.ph'],
            [
                'empNo' => '10030947',
                'username' => 'John Christopher Llobrera',
                'password' => Hash::make('password'),
            ]
        );

        // Assign the role
        $user->assignRole($role);

    }
}
