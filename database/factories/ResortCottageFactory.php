<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ResortType;
use App\Models\ResortCottage;
use Illuminate\Support\Str;

class ResortCottageFactory extends Factory
{
    protected $model = ResortCottage::class;

    public function definition()
    {
        $resortType = ResortType::inRandomOrder()->first() ?? ResortType::factory()->create();

        return [
            'cottage_name' => $resortType->type_name . ' Cottage ' . $this->faker->unique()->numberBetween(1, 50),
            'resort_type_id' => $resortType->id,
            'cottage_status' => $this->faker->randomElement(['Available', 'Maintenance']),
            'cottage_rate' => $this->faker->randomFloat(2, 150, 600),
            'cottage_capacity' => $this->faker->numberBetween(4, 8),
            'picture' => 'img/cottages/' . Str::slug($resortType->type_name) . '.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
