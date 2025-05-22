<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\HotelRoom;
use App\Models\HotelView;

class HotelViewFactory extends Factory
{
    protected $model = HotelView::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $room = HotelRoom::inRandomOrder()->first() ?? HotelRoom::factory()->create();

        return [
            'hotel_room_id' => $room->id,
            'user_id' => $this->faker->randomElement([$user->id, null]),
            'ip_address' => $this->faker->ipv4,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
