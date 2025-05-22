<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ResortCottage;
use App\Models\ResortTransaction;
use Illuminate\Support\Str;

class ResortTransactionFactory extends Factory
{
    protected $model = ResortTransaction::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $cottage = ResortCottage::inRandomOrder()->first() ?? ResortCottage::factory()->create();

        return [
            'transaction_number' => 'RTX-' . Str::random(10),
            'user_id' => $user->id,
            'customer_name' => $this->faker->name,
            'customer_number' => $this->faker->phoneNumber,
            'customer_email' => $this->faker->email,
            'customer_type' => $this->faker->randomElement(['Registered', 'Walk-in', 'Online']),
            'resort_cottage_id' => $cottage->id,
            'booking_date' => $this->faker->dateTimeBetween('now', '+10 days'),
            'total_amount' => $cottage->cottage_rate * $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement(['Pending', 'Confirmed', 'Completed', 'Cancelled', 'No-show']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
