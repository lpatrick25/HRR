<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone_number' => '1234567890',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'active' => true,
                'user_role' => 'Owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Alice',
                'last_name' => 'Smith',
                'phone_number' => '0987654321',
                'email' => 'alice.smith@example.com',
                'password' => Hash::make('password123'),
                'active' => true,
                'user_role' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Brown',
                'phone_number' => '1122334455',
                'email' => 'bob.brown@example.com',
                'password' => Hash::make('password123'),
                'active' => true,
                'user_role' => 'Front Desk - Hotel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
