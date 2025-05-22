<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ResortCottage;
use App\Models\ResortReview;

class ResortReviewFactory extends Factory
{
    protected $model = ResortReview::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $cottage = ResortCottage::inRandomOrder()->first() ?? ResortCottage::factory()->create();

        return [
            'user_id' => $user->id,
            'resort_cottage_id' => $cottage->id,
            'review' => $this->faker->paragraph,
            'rating' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement(['Pending', 'Approved', 'Rejected']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
