<?php

namespace App\DataTransferObjects\Obligations;

readonly class ObligationData
{
    public function __construct(
        public int $company_id,
        public ?int $monitoring_id,
        public ?int $environmental_authority_id,
        public ?string $resolution_number,
        public ?string $resolution_date,
        public ?string $instrument_type,
        public string $name,
        public ?string $description,
        public ?string $legal_basis,
        public ?string $environmental_medium,
        public ?string $obligation_type,
        public ?string $compliance_deadline,
        public ?string $compliance_frequency,
        public string $status = 'vigente',
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            company_id:                 $validated['company_id'],
            monitoring_id:              $validated['monitoring_id'] ?? null,
            environmental_authority_id: $validated['environmental_authority_id'] ?? null,
            resolution_number:          $validated['resolution_number'] ?? null,
            resolution_date:            $validated['resolution_date'] ?? null,
            instrument_type:            $validated['instrument_type'] ?? null,
            name:                       $validated['name'],
            description:                $validated['description'] ?? null,
            legal_basis:                $validated['legal_basis'] ?? null,
            environmental_medium:       $validated['environmental_medium'] ?? null,
            obligation_type:            $validated['obligation_type'] ?? null,
            compliance_deadline:        $validated['compliance_deadline'] ?? null,
            compliance_frequency:       $validated['compliance_frequency'] ?? null,
            status:                     $validated['status'] ?? 'vigente',
        );
    }
}
