<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newPermissions = [
            // Review Management
            'review_view',
            'review_create',
            'review_edit',
            'review_delete',
        ];
        // Create only new permissions that don't exist
        $createdPermissions = [];
        foreach ($newPermissions as $permission) {
            $existingPermission = Permission::where('name', $permission)->first();
            if (!$existingPermission) {
                $createdPermissions[] = Permission::create(['name' => $permission]);
                $this->command->info("Created permission: {$permission}");
            } else {
                $createdPermissions[] = $existingPermission;
                $this->command->line("Permission already exists: {$permission}");
            }
        }

        // Get admin role
        $admin = Role::where('name', 'admin')->first();

        if ($admin) {
            // Get all permissions (existing + new)
            $allPermissions = Permission::all();

            // Sync all permissions to admin
            $admin->syncPermissions($allPermissions);

            $this->command->info("Admin role synced with {$allPermissions->count()} permissions");
        } else {
            $this->command->error('Admin role not found!');
        }

    }
}