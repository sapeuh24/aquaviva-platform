<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\EnvironmentalMedium;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        return [
            'company_id'              => Company::factory(),
            'environmental_medium_id' => EnvironmentalMedium::factory(),
            'name'                    => $this->faker->sentence(3),
            'code'                    => $this->faker->unique()->bothify('PROG-####'),
            'description'             => $this->faker->paragraph(),
            'is_active'               => true,
        ];
    }

    public function inactivo(): static
    {
        return $this->state(['is_active' => false]);
    }
}
