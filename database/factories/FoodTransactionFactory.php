<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Food;
use App\Models\FoodTransaction;
use Illuminate\Support\Str;

class FoodTransactionFactory extends Factory
{
    protected $model = FoodTransaction::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $food = Food::inRandomOrder()->first() ?? Food::factory()->create();
        $quantity = $this->faker->numberBetween(1, 10);

        return [
            'transaction_number' => 'FTX-' . Str::random(10),
            'user_id' => $user->id,
            'customer_name' => $this->faker->name,
            'customer_number' => $this->faker->phoneNumber,
            'customer_email' => $this->faker->email,
            'customer_type' => $this->faker->randomElement(['Registered', 'Walk-in']),
            'food_id' => $food->id,
            'quantity' => $quantity,
            'total_amount' => $food->food_price * $quantity,
            'reservation_date' => $this->faker->dateTimeBetween('now', '+10 days'),
            'status' => $this->faker->randomElement(['Pending', 'Confirmed', 'Completed', 'Cancelled', 'No-show']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
