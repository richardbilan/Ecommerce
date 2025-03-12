<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'richard@admin.com',
                'type' => 1, // 1 for admin
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Manager User',
                'email' => 'richard@manager.com',
                'type' => 2,
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'User',
                'email' => 'richard@user.com',
                'type' => 0,
                'password' => Hash::make('123456'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']], // Check if the email exists
                $user // If exists, update; if not, create new user
            );
        }
    }
}
