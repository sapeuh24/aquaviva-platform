<?php

namespace App\Actions\OrganizationProjects;

use App\Repositories\Contracts\OrganizationProjectRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListOrganizationProjectsAction
{
    public function __construct(
        private readonly OrganizationProjectRepositoryInterface $repository,
    ) {}

    public function execute(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
