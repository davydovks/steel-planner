<?php

namespace Database\Seeders;

use App\Models\CustomerType;
use Illuminate\Database\Seeder;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerType::create(['name' => 'Физическое лицо']);
        CustomerType::create(['name' => 'Юридическое лицо']);
    }
}
