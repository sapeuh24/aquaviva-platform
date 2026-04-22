<?php

namespace App\DataTransferObjects\Worksheets;

readonly class WorksheetData
{
    public function __construct(
        public int $company_id,
        public ?int $obligation_id,
        public ?int $monitoring_id,
        public ?string $monitoring_tool,
        public ?string $monitoring_phase,
        public ?string $name,
        public ?string $objective,
        public ?string $target,
        public ?string $observations,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            company_id:       $validated['company_id'],
            obligation_id:    $validated['obligation_id'] ?? null,
            monitoring_id:    $validated['monitoring_id'] ?? null,
            monitoring_tool:  $validated['monitoring_tool'] ?? null,
            monitoring_phase: $validated['monitoring_phase'] ?? null,
            name:             $validated['name'] ?? null,
            objective:        $validated['objective'] ?? null,
            target:           $validated['target'] ?? null,
            observations:     $validated['observations'] ?? null,
        );
    }
}
