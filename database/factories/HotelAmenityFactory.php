<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HotelRoom;
use App\Models\HotelAmenity;

class HotelAmenityFactory extends Factory
{
    protected $model = HotelAmenity::class;

    public function definition()
    {
        $room = HotelRoom::inRandomOrder()->first() ?? HotelRoom::factory()->create();

        return [
            'hotel_room_id' => $room->id,
            'amenity' => $this->faker->randomElement([
                'Wi-Fi',
                'Air Conditioning',
                'Mini Bar',
                'TV',
                'Room Service',
                'Safe'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
