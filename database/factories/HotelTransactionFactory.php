<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\HotelRoom;
use App\Models\HotelTransaction;
use Illuminate\Support\Str;

class HotelTransactionFactory extends Factory
{
    protected $model = HotelTransaction::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $room = HotelRoom::inRandomOrder()->first() ?? HotelRoom::factory()->create();
        $checkIn = $this->faker->dateTimeBetween('now', '+10 days');
        $checkOut = $this->faker->dateTimeBetween($checkIn, '+20 days');

        return [
            'transaction_number' => 'HTX-' . Str::random(10),
            'user_id' => $user->id,
            'customer_name' => $this->faker->name,
            'customer_number' => $this->faker->phoneNumber,
            'customer_email' => $this->faker->email,
            'customer_type' => $this->faker->randomElement(['Registered', 'Walk-in', 'Online']),
            'hotel_room_id' => $room->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'total_amount' => $room->room_rate * $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement([
                'Pending',
                'Confirmed',
                'Checked-in',
                'Checked-out',
                'Cancelled',
                'No-show',
                'Walk-in'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
