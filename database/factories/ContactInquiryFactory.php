<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ContactInquiry;

class ContactInquiryFactory extends Factory
{
    protected $model = ContactInquiry::class;

    public function definition()
    {
        return [
            'customer_full_name' => $this->faker->name,
            'customer_email' => $this->faker->email,
            'customer_subject' => $this->faker->sentence(4),
            'customer_message' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['Unread', 'Read']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
