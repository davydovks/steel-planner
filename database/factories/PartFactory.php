<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Part>
 */
class PartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $profiles = ['PK250X6.0_30245_2003', 'PL8', 'PL10', 'PL20', 'L125X10_8509_93'];
        return [
            'order_id' => Order::inRandomOrder()->first(),
            'position' => fake()->numberBetween(1, 200),
            'profile' => $profiles[array_rand($profiles)],
            'weight' => fake()->numberBetween(1, 400) * 100,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
