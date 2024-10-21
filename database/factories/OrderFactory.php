<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'customer_id' => Customer::inRandomOrder()->first(),
            'due_date' => fake()->dateTimeInInterval('+10 days', '+30 days'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
