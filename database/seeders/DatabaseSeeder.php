<?php

namespace Database\Seeders;

use App\Models\Catalog;
use App\Models\Employee;
use App\Models\Laundry;
use App\Models\Parfume;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        Laundry::factory()->count(10)->create()
            ->each(function ($laundry) {
                Catalog::factory()->count(5)->for($laundry)->create();
                Parfume::factory()->count(rand(1, 3))->for($laundry)->create();
                Employee::factory()->count(rand(1, 5))->for($laundry)->create();
            });
    }
}
