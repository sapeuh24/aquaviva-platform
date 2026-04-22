<?php

namespace Database\Factories;

use App\Models\IndicatorFrequency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IndicatorFrequency>
 */
class IndicatorFrequencyFactory extends Factory
{
    protected $model = IndicatorFrequency::class;

    public function definition(): array
    {
        return [
            'name'            => $this->faker->unique()->word(),
            'months_interval' => $this->faker->numberBetween(1, 12),
        ];
    }
}
