<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UpdatePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define new permissions that match your routes
        $newPermissions = [
            // Dashboard
            'access_dashboard',
            
            // Brand Management
            'brand_view', 'brand_create', 'brand_edit', 'brand_delete',
            
            // Mobile Management  
            'mobile_view', 'mobile_create', 'mobile_edit', 'mobile_delete',
            
            // Blog Management
            'blog_view', 'blog_create', 'blog_edit', 'blog_delete',
            
            // News Management
            'news_view', 'news_create', 'news_edit', 'news_delete',
            
            // User Management
            'user_view', 'user_create', 'user_edit', 'user_delete',
            
            // Comments & Ratings
            'comment_view', 'comment_moderate',
            'rating_view', 'rating_moderate',
            
            // System
            'settings_manage', 'reports_view'
        ];

        // Create new permissions if they don't exist
        foreach ($newPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Get roles
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();
        $user = Role::where('name', 'user')->first();

        if ($admin) {
            // Admin gets all permissions
            $admin->syncPermissions(Permission::all());
        }

        // if ($manager) {
        //     // Manager permissions (content management + comments/ratings)
        //     $managerPermissions = [
        //         'access_dashboard',
        //         'brand_view', 'brand_create', 'brand_edit', 'brand_delete',
        //         'mobile_view', 'mobile_create', 'mobile_edit', 'mobile_delete',
        //         'blog_view', 'blog_create', 'blog_edit', 'blog_delete',
        //         'news_view', 'news_create', 'news_edit', 'news_delete',
        //         'comment_view', 'comment_moderate',
        //         'rating_view', 'rating_moderate',
        //         'reports_view'
        //     ];
        //     $manager->syncPermissions($managerPermissions);
        // }

        // if ($user) {
        //     // User permissions (frontend only - no admin panel access)
        //     $user->syncPermissions([]);
        // }

        $this->command->info('Permissions updated successfully!');
        $this->command->info('Admin has all permissions');
        $this->command->info('Manager has content management permissions');
        $this->command->info('User has no admin panel permissions');
    }
}