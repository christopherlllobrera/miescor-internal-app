<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Data
        $this->call([
            DepartmentSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            BusinessUnitsSeeder::class,
            LocationSeeder::class,
        ]);

        // Employee Portal
        $this->call([
            CarouselSeeder::class,
            EventSeeder::class,
            DepartmentModuleSeeder::class,
            // DownloadableModuleSeeder::class,
            FAQModulesSeeder::class,
            FaqTagModulesSeeder::class,
            ICTFaqModulesSeeder::class,
            EmployeePortalRoleSeeder::class,
            SettingPermissionSeeder::class,
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Users
        $this->call([
            EmployePortalUser::class,
            UserSeeder::class,
        ]);
    }
}
