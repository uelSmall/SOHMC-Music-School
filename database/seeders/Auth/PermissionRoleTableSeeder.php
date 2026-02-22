<?php

namespace Database\Seeders\Auth;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->CreateDefaultPermissions();

        /**
         * Create Roles and Assign Permissions to Roles.
         */
        $super_admin = Role::firstOrCreate(['name' => 'super admin']);
        $super_admin->syncPermissions([
            'view_backend',
            'edit_settings',
            'manage_lessons',
            'assign_lessons',
            'view_assigned_lessons',
        ]);

        $admin = Role::firstOrCreate(['name' => 'administrator']);
        $admin->syncPermissions([
            'view_backend',
            'edit_settings',
            'manage_lessons',
            'assign_lessons',
        ]);

        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $teacher->syncPermissions([
            'view_backend',
            'manage_lessons',
            'assign_lessons',
        ]);

        $student = Role::firstOrCreate(['name' => 'student']);
        $student->syncPermissions(['view_assigned_lessons']);
    }

    public function CreateDefaultPermissions()
    {
        // Create Permissions
        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        Artisan::call('auth:permissions', [
            'name' => 'posts',
        ]);
        if (! app()->runningUnitTests()) {
            $this->command->info('_Posts_ Permissions Created.');
        }

        Artisan::call('auth:permissions', [
            'name' => 'categories',
        ]);
        if (! app()->runningUnitTests()) {
            $this->command->info('_Categories_ Permissions Created.');
        }

        Artisan::call('auth:permissions', [
            'name' => 'tags',
        ]);
        if (! app()->runningUnitTests()) {
            $this->command->info('_Tags_ Permissions Created.');
        }

        Artisan::call('auth:permissions', [
            'name' => 'comments',
        ]);
        if (! app()->runningUnitTests()) {
            $this->command->info('_Comments_ Permissions Created.');
        }
    }
}
