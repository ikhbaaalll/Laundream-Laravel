<?php

namespace Database\Seeders;

use App\Models\Laundry;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN,
                'no_hp' => '082246106612'
            ]
        );

        $owner = User::create(
            [
                'name' => 'owner',
                'email' => 'owner@owner.com',
                'password' => bcrypt('password'),
                'role' => User::ROLE_OWNER,
                'no_hp' => '082246106613'
            ]
        );

        Laundry::factory()->for($owner)->create();
    }
}
