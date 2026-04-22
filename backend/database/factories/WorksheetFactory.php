<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Obligation;
use App\Models\Worksheet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Worksheet>
 */
class WorksheetFactory extends Factory
{
    protected $model = Worksheet::class;

    public function definition(): array
    {
        return [
            'company_id'       => Company::factory(),
            'obligation_id'    => null,
            'monitoring_id'    => null,
            'monitoring_tool'  => 'Medidor de caudal',
            'monitoring_phase' => 'Construccion',
            'name'             => $this->faker->sentence(3),
            'objective'        => $this->faker->sentence(),
            'target'           => $this->faker->sentence(),
            'observations'     => $this->faker->paragraph(),
        ];
    }
}
