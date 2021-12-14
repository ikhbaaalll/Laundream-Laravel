<?php

namespace Database\Seeders;

use App\Models\Laundry;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        Laundry::factory()->count(10)->create();
    }
}
