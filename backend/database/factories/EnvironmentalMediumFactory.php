<?php

namespace Database\Factories;

use App\Models\EnvironmentalMedium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EnvironmentalMedium>
 */
class EnvironmentalMediumFactory extends Factory
{
    protected $model = EnvironmentalMedium::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
        ];
    }
}
