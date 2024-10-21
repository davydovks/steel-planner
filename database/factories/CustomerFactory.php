<?php

namespace Database\Factories;

use App\Models\CustomerType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'customer_type_id' => CustomerType::inRandomOrder()->first(),
            'address' => fake()->address(),
            'TIN' => fake()->unique()->inn(),
            'created_at' => now(),
            'updated_at'=> now(),
        ];
    }
}
