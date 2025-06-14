<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FoodCategory;
use App\Models\Food;
use Illuminate\Support\Str;

class FoodFactory extends Factory
{
    protected $model = Food::class;

    public function definition()
    {
        $foodCategory = FoodCategory::inRandomOrder()->first() ?? FoodCategory::factory()->create();

        return [
            'food_name' => $foodCategory->category_name . ' ' . $this->faker->word . ' ' . $this->faker->numberBetween(1, 10),
            'food_category_id' => $foodCategory->id,
            'food_price' => $this->faker->randomFloat(2, 5, 50),
            'food_status' => $this->faker->randomElement(['Available', 'Not Available']),
            'food_unit' => $this->faker->randomElement([
                'Piece',
                'Slice',
                'Serving',
                'Platter',
                'Plate',
                'Set',
                'Combo',
                'Milliliter',
                'Liter',
                'Cup',
                'Glass',
                'Bottle',
                'Can'
            ]),
            'picture' => 'img/rooms/' . Str::slug($foodCategory->category_name) . '.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
