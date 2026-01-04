<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'homereview_slider_view',
            'homereview_slider_create',
            'homereview_slider_edit',
            'homereview_slider_delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'homereview_slider_view',
            'homereview_slider_create',
            'homereview_slider_edit',
            'homereview_slider_delete',
        ];

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->revokePermissionTo($permissions);
        }

        Permission::whereIn('name', $permissions)->delete();
    }
};
