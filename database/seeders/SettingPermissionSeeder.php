<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SettingPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'Create-Permissions']);
        Permission::create(['name' => 'View-Permissions']);
        Permission::create(['name' => 'Update-Permissions']);
        Permission::create(['name' => 'Delete-Permissions']);

        Permission::create(['name' => 'Create-Role']);
        Permission::create(['name' => 'View-Role']);
        Permission::create(['name' => 'Update-Role']);
        Permission::create(['name' => 'Delete-Role']);

        Permission::create(['name' => 'Create-User']);
        Permission::create(['name' => 'View-User']);
        Permission::create(['name' => 'Update-User']);
        Permission::create(['name' => 'Delete-User']);

        $this->command->info('Setting Permissions created successfully!');
    }
}
