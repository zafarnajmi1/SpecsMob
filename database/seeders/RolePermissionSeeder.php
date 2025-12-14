<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or fetch roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Define permissions list (merged)
        $permissions = [
            // General
            'view dashboard', 'view reports',

            // User management
            'manage users', 'manager manage', 'user manage', 'analytics view',

            // Brand
            'brand create', 'brand edit', 'brand delete', 'brand view',

            // Mobile
            'mobile create', 'mobile edit', 'mobile delete', 'mobile view',

            // Blog
            'blog create', 'blog edit', 'blog delete', 'blog view',

            // News
            'news create', 'news edit', 'news delete', 'news view',

            // Comments & Ratings
            'comment moderate', 'rating moderate',
        ];

        // Create permissions if not exist
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign permissions
        $admin->syncPermissions(Permission::all());

        $manager->syncPermissions([
            'view dashboard', 'view reports',
            'mobile create', 'mobile edit', 'mobile delete', 'mobile view',
            'blog create', 'blog edit', 'blog delete', 'blog view',
            'news create', 'news edit', 'news delete', 'news view',
        ]);

        $user->syncPermissions([
            'view dashboard',
            'mobile view', 'blog view', 'news view'
        ]);
    }
}
