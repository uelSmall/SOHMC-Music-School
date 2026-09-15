<?php

namespace Database\Seeders\Auth;

use App\Events\Backend\UserCreated;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'username' => '100001',
                'name' => 'Super Admin',
                'email' => 'super@admin.com',
                'password' => 'password',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'username' => '100002',
                'name' => 'Malchiel Small',
                'email' => 'admin@admin.com',
                'password' => 'password',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user_data) {
            $user = User::where('email', $user_data['email'])->first();

            if ($user) {
                $user->forceFill([
                    'username' => $user_data['username'],
                    'email_verified_at' => $user_data['email_verified_at'],
                ])->save();

                continue;
            }

            $user = User::create([
                'username' => $user_data['username'],
                'name' => $user_data['name'],
                'email' => $user_data['email'],
                'password' => $user_data['password'],
                'email_verified_at' => $user_data['email_verified_at'],
            ]);

            event(new UserCreated($user));
        }
    }
}
