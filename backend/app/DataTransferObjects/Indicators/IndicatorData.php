<?php

namespace App\DataTransferObjects\Indicators;

readonly class IndicatorData
{
    public function __construct(
        public int $company_id,
        public int $worksheet_id,
        public int $indicator_frequency_id,
        public string $name,
        public ?string $objective,
        public ?string $target,
        public ?string $next_due_date,
        public bool $is_active = true,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            company_id:             $validated['company_id'],
            worksheet_id:           $validated['worksheet_id'],
            indicator_frequency_id: $validated['indicator_frequency_id'],
            name:                   $validated['name'],
            objective:              $validated['objective'] ?? null,
            target:                 $validated['target'] ?? null,
            next_due_date:          $validated['next_due_date'] ?? null,
            is_active:              $validated['is_active'] ?? true,
        );
    }
}
