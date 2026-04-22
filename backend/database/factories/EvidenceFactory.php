<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Evidence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evidence>
 */
class EvidenceFactory extends Factory
{
    protected $model = Evidence::class;

    public function definition(): array
    {
        return [
            'company_id'    => Company::factory(),
            'activity_id'   => Activity::factory(),
            'uploaded_by'   => User::factory(),
            'original_name' => $this->faker->word() . '.pdf',
            'storage_path'  => 'evidences/' . $this->faker->uuid() . '.pdf',
            'mime_type'     => 'application/pdf',
            'size_bytes'    => $this->faker->numberBetween(1024, 5242880),
            'description'   => $this->faker->sentence(),
        ];
    }

    public function imagen(): static
    {
        return $this->state([
            'original_name' => $this->faker->word() . '.jpg',
            'storage_path'  => 'evidences/' . $this->faker->uuid() . '.jpg',
            'mime_type'     => 'image/jpeg',
        ]);
    }
}
