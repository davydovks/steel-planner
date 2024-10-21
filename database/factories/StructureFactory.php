<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Structure>
 */
class StructureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Б-1', 'Б-2', 'Б-3', 'Б-4', 'Б-5', 'Б-6', 'Б-7',
            'К-1', 'К-2', 'К-3', 'К-4', 'К-5', 'К-6', 'К-7',
            'П-1', 'П-2', 'П-3', 'П-4', 'П-5',
            'К-1', 'К-2', 'К-3', 'К-4', 'К-5', 'К-6', 'К-7',
            'СВ-1', 'СВ-2', 'СВ-3', 'СВ-4',
            'СГ-1', 'СГ-2', 'СГ-3', 'СГ-4', 'СГ-5',
        ];
        return [
            'order_id' => Order::inRandomOrder()->first(),
            'name' => $names[fake()->unique()->numberBetween(0, count($names) - 1)],
            'quantity' => fake()->numberBetween(1, 30),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
