<?php

namespace Database\Seeders;

use App\Events\Backend\UserCreated;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('users', 'id'), COALESCE((SELECT MAX(id) FROM users), 1))");
        }

        $users = [
            // Super Admin
            [
                'username' => '100001',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'name' => 'Super Admin',
                'email' => 'super@admin.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ],
            // Admin
            [
                'username' => '100002',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ],
            // Parent
            [
                'username' => '100003',
                'first_name' => 'Patricia',
                'last_name' => 'Jones',
                'name' => 'Parent One',
                'email' => 'parent1@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ],
            // Teachers
            [
                'username' => '100006',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'name' => 'Teacher One',
                'email' => 'teacher1@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ],
            [
                'username' => '100007',
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'name' => 'Teacher Two',
                'email' => 'teacher2@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ],
            // Students
            [
                'username' => '100008',
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'name' => 'Student One',
                'email' => 'student1@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ],
            [
                'username' => '100009',
                'first_name' => 'Bob',
                'last_name' => 'Williams',
                'name' => 'Student Two',
                'email' => 'student2@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ],
            [
                'username' => '100010',
                'first_name' => 'Carol',
                'last_name' => 'Brown',
                'name' => 'Student Three',
                'email' => 'student3@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user_data) {
            $user = User::updateOrCreate(
                ['email' => $user_data['email']],
                $user_data
            );

            if (empty($user->username) && isset($user_data['username'])) {
                $user->username = $user_data['username'];
                $user->save();
            }

            event(new UserCreated($user));

            // Assign roles based on email pattern
            if (str_contains($user_data['email'], 'teacher')) {
                $user->syncRoles(['teacher']);
            } elseif (str_contains($user_data['email'], 'student')) {
                $user->syncRoles(['student']);
            } elseif (str_contains($user_data['email'], 'parent')) {
                $user->syncRoles(['parent']);
            } elseif (str_contains($user_data['email'], 'admin')) {
                if ($user_data['email'] === 'super@admin.com') {
                    $user->syncRoles(['super admin']);
                } else {
                    $user->syncRoles(['administrator']);
                }
            }
        }
    }
}
