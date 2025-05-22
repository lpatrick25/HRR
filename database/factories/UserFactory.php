<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone_number' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password123'), // Default password
            'active' => $this->faker->boolean(80), // 80% chance of being true
            'news_letter' => $this->faker->boolean(20), // 20% chance of being true
            'user_role' => $this->faker->randomElement([
                'Owner',
                'Admin',
                'Front Desk - Hotel',
                'Front Desk - Resort',
                'Front Desk - Food',
                'Customer'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
