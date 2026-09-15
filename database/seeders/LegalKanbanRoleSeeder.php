<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class LegalKanbanRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'view-kanban-board-all',
            'view-kanban-board-in-progress',
            'view-kanban-board-for-approval',
            'view-kanban-board-due',
            'move-kanban-board-all',
            'move-kanban-board-in-progress-to-for-approval',
            'move-kanban-board-in-progress-to-due',
            'create-contract',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles and their permissions
        $rolePermissions = [
            'Legal Staff' => [
                'view-kanban-board-all',
                'move-kanban-board-all',
                'create-contract',
            ],
            'Legal Counsel' => [
                'view-kanban-board-in-progress',
                'view-kanban-board-for-approval',
                'view-kanban-board-due',
                'move-kanban-board-in-progress-to-for-approval',
                'move-kanban-board-in-progress-to-due',
            ],
            'General Counsel' => [
                'view-kanban-board-all',
                'move-kanban-board-all',
            ],
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            // Give permissions to the role
            $role->syncPermissions($perms);
        }

        $this->command->info('Legal Kanban roles and permissions seeded successfully!');
    }
}
