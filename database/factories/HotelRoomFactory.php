<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HotelType;
use App\Models\HotelRoom;
use Illuminate\Support\Str;

class HotelRoomFactory extends Factory
{
    protected $model = HotelRoom::class;

    public function definition()
    {
        $hotelType = HotelType::inRandomOrder()->first() ?? HotelType::factory()->create();

        return [
            'room_name' => $hotelType->type_name . ' Room ' . $this->faker->unique()->numberBetween(101, 999),
            'hotel_type_id' => $hotelType->id,
            'room_status' => $this->faker->randomElement(['Available', 'Maintenance']),
            'room_rate' => $this->faker->randomFloat(2, 100, 500),
            'room_capacity' => $this->faker->numberBetween(2, 6),
            'picture' => 'img/rooms/' . Str::slug($hotelType->type_name) . '.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
