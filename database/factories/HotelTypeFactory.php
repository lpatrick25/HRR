<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HotelType;

class HotelTypeFactory extends Factory
{
    protected $model = HotelType::class;

    public function definition()
    {
        return [
            'type_name' => $this->faker->unique()->randomElement([
                'Standard',
                'Deluxe',
                'Suite',
                'Executive',
                'Family',
                'Penthouse',
                'Economy'
            ]),
            'type_description' => $this->faker->sentence(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
