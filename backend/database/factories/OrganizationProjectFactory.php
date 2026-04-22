<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\OrganizationProject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganizationProject>
 */
class OrganizationProjectFactory extends Factory
{
    protected $model = OrganizationProject::class;

    public function definition(): array
    {
        return [
            'company_id'      => Company::factory(),
            'municipality_id' => null,
            'name'            => $this->faker->sentence(3),
            'code'            => $this->faker->unique()->bothify('PROJ-####'),
            'description'     => $this->faker->paragraph(),
            'location'        => $this->faker->city(),
            'start_date'      => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'end_date'        => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'status'          => 'activo',
        ];
    }
}
