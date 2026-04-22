<?php

namespace App\Actions\Alerts;

use App\Repositories\Contracts\AlertRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListAlertsAction
{
    public function __construct(
        private readonly AlertRepositoryInterface $repository,
    ) {}

    public function execute(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
