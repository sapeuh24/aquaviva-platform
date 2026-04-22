<?php

namespace App\DataTransferObjects\Evidences;

readonly class EvidenceData
{
    public function __construct(
        public int $company_id,
        public int $activity_id,
        public int $uploaded_by,
        public string $original_name,
        public string $storage_path,
        public string $mime_type,
        public int $size_bytes,
        public ?string $description,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            company_id:    $validated['company_id'],
            activity_id:   $validated['activity_id'],
            uploaded_by:   $validated['uploaded_by'],
            original_name: $validated['original_name'],
            storage_path:  $validated['storage_path'],
            mime_type:     $validated['mime_type'],
            size_bytes:    $validated['size_bytes'],
            description:   $validated['description'] ?? null,
        );
    }
}
