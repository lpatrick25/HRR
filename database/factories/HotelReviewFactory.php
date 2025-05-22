<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\HotelRoom;
use App\Models\HotelReview;

class HotelReviewFactory extends Factory
{
    protected $model = HotelReview::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $room = HotelRoom::inRandomOrder()->first() ?? HotelRoom::factory()->create();

        return [
            'user_id' => $user->id,
            'hotel_room_id' => $room->id,
            'review' => $this->faker->paragraph,
            'rating' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement(['Pending', 'Approved', 'Rejected']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
