<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Indicator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'company_id'        => Company::factory(),
            'indicator_id'      => Indicator::factory(),
            'name'              => $this->faker->sentence(4),
            'description'       => $this->faker->paragraph(),
            'scheduled_date'    => $this->faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'executed_date'     => null,
            'compliance_status' => 'pending',
            'notes'             => $this->faker->sentence(),
        ];
    }

    public function cumplida(): static
    {
        return $this->state([
            'compliance_status' => 'completed',
            'executed_date'     => now()->toDateString(),
        ]);
    }
}
