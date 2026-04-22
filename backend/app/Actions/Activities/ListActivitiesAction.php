<?php

namespace App\Actions\Activities;

use App\Repositories\Contracts\ActivityRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListActivitiesAction
{
    public function __construct(
        private readonly ActivityRepositoryInterface $repository,
    ) {}

    public function execute(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
