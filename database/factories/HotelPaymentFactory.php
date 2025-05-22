<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HotelTransaction;
use App\Models\HotelPayment;
use Illuminate\Support\Str;

class HotelPaymentFactory extends Factory
{
    protected $model = HotelPayment::class;

    public function definition()
    {
        $transaction = HotelTransaction::inRandomOrder()->first() ?? HotelTransaction::factory()->create();

        return [
            'hotel_transaction_id' => $transaction->id,
            'payment_method' => $this->faker->randomElement(['Cash', 'Online']),
            'total_amount' => $transaction->total_amount,
            'amount_paid' => $transaction->total_amount * $this->faker->randomFloat(2, 0.5, 1),
            'checkout_session_id' => 'cs_' . Str::random(20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
