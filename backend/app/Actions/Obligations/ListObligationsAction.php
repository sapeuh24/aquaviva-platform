<?php

namespace App\Actions\Obligations;

use App\Repositories\Contracts\ObligationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListObligationsAction
{
    public function __construct(
        private readonly ObligationRepositoryInterface $repository,
    ) {}

    public function execute(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
