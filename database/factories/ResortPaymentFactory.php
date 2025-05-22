<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ResortTransaction;
use App\Models\ResortPayment;
use Illuminate\Support\Str;

class ResortPaymentFactory extends Factory
{
    protected $model = ResortPayment::class;

    public function definition()
    {
        $transaction = ResortTransaction::inRandomOrder()->first() ?? ResortTransaction::factory()->create();

        return [
            'resort_transaction_id' => $transaction->id,
            'payment_method' => $this->faker->randomElement(['Cash', 'Online']),
            'total_amount' => $transaction->total_amount,
            'amount_paid' => $transaction->total_amount * $this->faker->randomFloat(2, 0.5, 1),
            'checkout_session_id' => 'cs_' . Str::random(20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
