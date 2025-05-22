<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FoodCategory;

class FoodCategoryFactory extends Factory
{
    protected $model = FoodCategory::class;

    public function definition()
    {
        return [
            'category_name' => $this->faker->unique()->randomElement([
                'Appetizers',
                'Main Course',
                'Desserts',
                'Beverages',
                'Breakfast',
                'Snacks'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
