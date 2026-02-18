<?php

namespace Database\Seeders;

use App\Events\Backend\UserCreated;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Super Admin (ID 1 - already exists from AuthTableSeeder)
            [
                'id' => 1,
                'username' => '100001',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'name' => 'Super Admin',
                'email' => 'super@admin.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Admin (ID 2 - already exists from AuthTableSeeder)
            [
                'id' => 2,
                'username' => '100002',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Teachers (IDs 6-7)
            [
                'id' => 6,
                'username' => '100006',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'name' => 'Teacher One',
                'email' => 'teacher1@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'username' => '100007',
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'name' => 'Teacher Two',
                'email' => 'teacher2@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Students (IDs 8-10)
            [
                'id' => 8,
                'username' => '100008',
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'name' => 'Student One',
                'email' => 'student1@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'username' => '100009',
                'first_name' => 'Bob',
                'last_name' => 'Williams',
                'name' => 'Student Two',
                'email' => 'student2@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'username' => '100010',
                'first_name' => 'Carol',
                'last_name' => 'Brown',
                'name' => 'Student Three',
                'email' => 'student3@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user_data) {
            $user = User::updateOrCreate(
                ['email' => $user_data['email']],
                $user_data
            );

            event(new UserCreated($user));

            // Assign roles based on email pattern
            if (str_contains($user_data['email'], 'teacher')) {
                $user->syncRoles(['teacher']);
            } elseif (str_contains($user_data['email'], 'student')) {
                $user->syncRoles(['student']);
            } elseif (str_contains($user_data['email'], 'admin')) {
                // Admins get appropriate roles based on context
                if ($user_data['email'] === 'super@admin.com') {
                    $user->syncRoles(['super admin']);
                } else {
                    $user->syncRoles(['administrator']);
                }
            }
        }
    }
}




