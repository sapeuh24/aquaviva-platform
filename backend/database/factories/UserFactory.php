<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'company_id'       => Company::factory(),
            'document_type_id' => DocumentType::factory(),
            'document_number'  => $this->faker->unique()->numerify('##########'),
            'first_name'       => $this->faker->firstName(),
            'last_name'        => $this->faker->lastName(),
            'email'            => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'         => static::$password ??= Hash::make('password'),
            'phone'            => $this->faker->phoneNumber(),
            'is_active'        => true,
            'remember_token'   => null,
        ];
    }

    public function inactivo(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }
}
