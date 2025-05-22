<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SeasonalPromo;
use Illuminate\Support\Str;

class SeasonalPromoFactory extends Factory
{
    protected $model = SeasonalPromo::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'start_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'end_date' => $this->faker->dateTimeBetween('+31 days', '+60 days'),
            'promo_code' => Str::upper($this->faker->unique()->lexify('PROMO????')),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
