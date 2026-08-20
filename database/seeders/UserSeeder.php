<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::updateOrCreate(['name' => 'superadmin']);

        $permissions = Permission::all();
        $role->syncPermissions($permissions);

        $admin = User::updateOrCreate(
            ['comp_email' => 'iamboss@miescor.ph'],
            [
                'empNo' => 'SuperAdmin',
                'username' => 'Administrator',
                'password' => Hash::make('passwordless'),
            ]
        );

        $admin->assignRole($role);

        $user2 = User::updateOrCreate(
            ['comp_email' => 'jclllobrera@miescor.ph'],
            [
                'empNo' => '10030947',
                'username' => 'John Christopher Llobrera',
                'password' => Hash::make('password'),
            ]
        );

        $user2->assignRole($role);
    }
}
