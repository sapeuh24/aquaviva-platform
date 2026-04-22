<?php

namespace App\DataTransferObjects\Programs;

readonly class ProgramData
{
    public function __construct(
        public int $company_id,
        public int $environmental_medium_id,
        public string $name,
        public string $code,
        public ?string $description,
        public bool $is_active = true,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            company_id: $validated['company_id'],
            environmental_medium_id: $validated['environmental_medium_id'],
            name: $validated['name'],
            code: $validated['code'],
            description: $validated['description'] ?? null,
            is_active: $validated['is_active'] ?? true,
        );
    }
}
