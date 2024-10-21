<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Part;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::all()->each(function ($order) {
            Part::factory(rand(10, 20))
                ->for($order)
                ->create();

            $order->structures()->each(function ($structure) use ($order) {
                $parts = $order->parts()
                    ->inRandomOrder()
                    ->limit(rand(3, 8))
                    ->get();
    
                $structure->parts()->attach($parts, ['quantity' => rand(1, 20)]);
            });
        });
    }
}
