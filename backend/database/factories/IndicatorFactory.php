<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Indicator;
use App\Models\IndicatorFrequency;
use App\Models\Worksheet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Indicator>
 */
class IndicatorFactory extends Factory
{
    protected $model = Indicator::class;

    public function definition(): array
    {
        return [
            'company_id'             => Company::factory(),
            'worksheet_id'           => Worksheet::factory(),
            'indicator_frequency_id' => IndicatorFrequency::factory(),
            'name'                   => $this->faker->sentence(4),
            'objective'              => $this->faker->sentence(),
            'target'                 => $this->faker->sentence(),
            'measurement_unit'       => '%',
            'baseline_value'         => $this->faker->randomFloat(2, 0, 100),
            'target_value'           => $this->faker->randomFloat(2, 50, 100),
            'next_due_date'          => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'is_active'              => true,
        ];
    }

    public function inactivo(): static
    {
        return $this->state(['is_active' => false]);
    }
}
