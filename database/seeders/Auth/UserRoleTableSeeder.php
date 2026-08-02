<?php

namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Class UserRoleTableSeeder.
 */
class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        User::where('email', 'super@admin.com')->first()?->syncRoles('super admin');
        User::where('email', 'admin@admin.com')->first()?->syncRoles('administrator');

        User::where('email', 'teacher1@example.com')->first()?->syncRoles('teacher');
        User::where('email', 'teacher2@example.com')->first()?->syncRoles('teacher');

        User::where('email', 'parent1@example.com')->first()?->syncRoles('parent');

        User::where('email', 'student1@example.com')->first()?->syncRoles('student');
        User::where('email', 'student2@example.com')->first()?->syncRoles('student');
        User::where('email', 'student3@example.com')->first()?->syncRoles('student');

        Artisan::call('cache:clear');
    }
}
