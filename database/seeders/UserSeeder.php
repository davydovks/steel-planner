<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => '',
            'last_name' => 'Администратор',
            'email' => 'admin@example.com',
            'password' => 'AdminPass',
            'owner' => true,
        ]);

        User::factory(4)->create();
    }
}
