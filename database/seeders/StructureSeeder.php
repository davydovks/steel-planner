<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Structure;
use Illuminate\Database\Seeder;

class StructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::all()->each(function ($order) {
            Structure::factory(rand(3, 7))
                ->for($order)
                ->create();
        });
    }
}
