<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FoodTransaction;
use App\Models\FoodPayment;
use Illuminate\Support\Str;

class FoodPaymentFactory extends Factory
{
    protected $model = FoodPayment::class;

    public function definition()
    {
        $transaction = FoodTransaction::inRandomOrder()->first() ?? FoodTransaction::factory()->create();

        return [
            'food_transaction_id' => $transaction->id,
            'payment_method' => $this->faker->randomElement(['Cash', 'Online']),
            'total_amount' => $transaction->total_amount,
            'amount_paid' => $transaction->total_amount * $this->faker->randomFloat(2, 0.5, 1),
            'checkout_session_id' => 'cs_' . Str::random(20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
