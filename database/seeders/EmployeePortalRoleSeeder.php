<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EmployeePortalRoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $actions = ['Create', 'View', 'Update', 'Delete'];

        /*
        |--------------------------------------------------------------------------
        | All resources
        |--------------------------------------------------------------------------
        */

        $resources = [
            'department-page',
            'downloadables',
            'faq-page',
            'faq-tag',
            'workflow',
            'workflow-tag',
            'directory-page',
            'carousel',
            'post',
            'categories',
            'comments',
            'events',
        ];

        /*
        |--------------------------------------------------------------------------
        | Create ALL permissions
        |--------------------------------------------------------------------------
        */

        foreach ($actions as $action) {
            foreach ($resources as $resource) {
                Permission::firstOrCreate([
                    'name' => "{$action}-{$resource}",
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Roles + their resources
        |--------------------------------------------------------------------------
        */

        $roleResources = [
            'Department PIC' => [
                'department-page',
                'downloadables',
                'faq-page',
                'faq-tag',
                'workflow',
                'workflow-tag',
                'directory-page',
            ],

            'TDE Team' => [
                'department-page',
                'downloadables',
                'faq-page',
                'faq-tag',
                'workflow',
                'workflow-tag',
                'directory-page',
                'carousel',
                'post',
                'categories',
                'comments',
                'events',
            ],

            'Marketing Team' => [
                'post',
                'categories',
                'comments',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Create roles + auto assign permissions
        |--------------------------------------------------------------------------
        */

        foreach ($roleResources as $roleName => $roleResourceList) {

            $role = Role::firstOrCreate(['name' => $roleName]);

            $permissionNames = [];

            foreach ($actions as $action) {
                foreach ($roleResourceList as $resource) {
                    $permissionNames[] = "{$action}-{$resource}";
                }
            }

            $role->syncPermissions($permissionNames);
        }

        $this->command->info('Employee Portal Roles & Permissions created successfully!');
    }
}
