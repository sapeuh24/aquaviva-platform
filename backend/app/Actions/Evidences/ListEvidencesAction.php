<?php

namespace App\Actions\Evidences;

use App\Repositories\Contracts\EvidenceRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListEvidencesAction
{
    public function __construct(
        private readonly EvidenceRepositoryInterface $repository,
    ) {}

    public function execute(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
