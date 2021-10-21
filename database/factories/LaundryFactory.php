<?php

namespace Database\Factories;

use App\Models\Laundry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaundryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Laundry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(['role' => User::ROLE_OWNER]),
            'name' => $this->faker->words(3, true),
            'status' => Laundry::STATUS_ACTIVE
        ];
    }
}
