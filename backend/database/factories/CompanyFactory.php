<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name'             => $this->faker->company(),
            'nit'              => $this->faker->unique()->numerify('#########-#'),
            'ciiu_code'        => $this->faker->bothify('??##'),
            'ciiu_description' => $this->faker->sentence(4),
            'municipality_id'  => null,
            'phone'            => $this->faker->phoneNumber(),
            'email'            => $this->faker->unique()->companyEmail(),
            'address'          => $this->faker->address(),
            'logo_path'        => null,
            'is_active'        => true,
        ];
    }

    public function inactiva(): static
    {
        return $this->state(['is_active' => false]);
    }
}
