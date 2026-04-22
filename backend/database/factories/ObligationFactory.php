<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Obligation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Obligation>
 */
class ObligationFactory extends Factory
{
    protected $model = Obligation::class;

    public function definition(): array
    {
        return [
            'company_id'                 => Company::factory(),
            'monitoring_id'              => null,
            'environmental_authority_id' => null,
            'resolution_number'          => $this->faker->numerify('RES-####-####'),
            'resolution_date'            => $this->faker->date(),
            'instrument_type'            => 'Licencia Ambiental',
            'name'                       => $this->faker->sentence(5),
            'description'                => $this->faker->paragraph(),
            'legal_basis'                => $this->faker->sentence(),
            'environmental_medium'       => 'Agua',
            'obligation_type'            => 'Monitoreo',
            'compliance_deadline'        => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'compliance_frequency'       => 'Trimestral',
            'status'                     => 'vigente',
        ];
    }

    public function vencida(): static
    {
        return $this->state(['status' => 'vencida']);
    }
}
