<?php

namespace App\DataTransferObjects\Alerts;

readonly class AlertData
{
    public function __construct(
        public int $company_id,
        public ?int $indicator_id,
        public string $type,
        public string $title,
        public string $message,
        public ?string $due_date,
        public string $status = 'pendiente',
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            company_id:   $validated['company_id'],
            indicator_id: $validated['indicator_id'] ?? null,
            type:         $validated['type'],
            title:        $validated['title'],
            message:      $validated['message'],
            due_date:     $validated['due_date'] ?? null,
            status:       $validated['status'] ?? 'pendiente',
        );
    }
}
