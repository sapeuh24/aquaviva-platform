<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\Evidences\EvidenceData;
use App\Models\Evidence;
use Illuminate\Pagination\LengthAwarePaginator;

interface EvidenceRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Evidence;

    public function create(EvidenceData $data): Evidence;

    public function softDelete(Evidence $evidence): void;

    public function purgeOlderThan(int $years): int;
}
