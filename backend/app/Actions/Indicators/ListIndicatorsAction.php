<?php

namespace App\Actions\Indicators;

use App\Repositories\Contracts\IndicatorRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListIndicatorsAction
{
    public function __construct(
        private readonly IndicatorRepositoryInterface $repository,
    ) {}

    public function execute(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
