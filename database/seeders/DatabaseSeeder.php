<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CustomerTypeSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            StructureSeeder::class,
            PartSeeder::class,
            UserSeeder::class,
        ]);
    }
}
