<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ResortCottage;
use App\Models\ResortView;

class ResortViewFactory extends Factory
{
    protected $model = ResortView::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $cottage = ResortCottage::inRandomOrder()->first() ?? ResortCottage::factory()->create();

        return [
            'resort_cottage_id' => $cottage->id,
            'user_id' => $this->faker->randomElement([$user->id, null]),
            'ip_address' => $this->faker->ipv4,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
