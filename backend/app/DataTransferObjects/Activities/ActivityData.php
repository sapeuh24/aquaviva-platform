<?php

namespace App\DataTransferObjects\Activities;

readonly class ActivityData
{
    public function __construct(
        public int $company_id,
        public int $indicator_id,
        public string $name,
        public ?string $description,
        public ?string $scheduled_date,
        public ?string $executed_date,
        public string $compliance_status,
        public ?string $notes,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            company_id:        $validated['company_id'],
            indicator_id:      $validated['indicator_id'],
            name:              $validated['name'],
            description:       $validated['description'] ?? null,
            scheduled_date:    $validated['scheduled_date'] ?? null,
            executed_date:     $validated['executed_date'] ?? null,
            compliance_status: $validated['compliance_status'] ?? 'pending',
            notes:             $validated['notes'] ?? null,
        );
    }
}
