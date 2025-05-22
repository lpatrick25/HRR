<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ResortType;

class ResortTypeFactory extends Factory
{
    protected $model = ResortType::class;

    public function definition()
    {
        return [
            'type_name' => $this->faker->unique()->randomElement([
                'Beachfront',
                'Garden View',
                'Ocean View',
                'Poolside',
                'Mountain View'
            ]),
            'type_description' => $this->faker->sentence(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
