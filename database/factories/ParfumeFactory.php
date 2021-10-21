<?php

namespace Database\Factories;

use App\Models\Laundry;
use App\Models\Parfume;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParfumeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Parfume::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'laundry_id' => Laundry::factory()
        ];
    }
}
