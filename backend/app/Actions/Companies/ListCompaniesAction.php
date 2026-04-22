<?php

namespace App\Actions\Companies;

use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListCompaniesAction
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {}

    public function execute(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
