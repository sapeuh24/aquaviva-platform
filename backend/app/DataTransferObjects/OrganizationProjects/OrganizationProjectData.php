<?php

namespace App\DataTransferObjects\OrganizationProjects;

readonly class OrganizationProjectData
{
    public function __construct(
        public int $company_id,
        public ?int $municipality_id,
        public string $name,
        public ?string $code,
        public ?string $description,
        public ?string $location,
        public ?string $start_date,
        public ?string $end_date,
        public string $status = 'activo',
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            company_id:      $validated['company_id'],
            municipality_id: $validated['municipality_id'] ?? null,
            name:            $validated['name'],
            code:            $validated['code'] ?? null,
            description:     $validated['description'] ?? null,
            location:        $validated['location'] ?? null,
            start_date:      $validated['start_date'] ?? null,
            end_date:        $validated['end_date'] ?? null,
            status:          $validated['status'] ?? 'activo',
        );
    }
}
