<?php

namespace App\Actions\Worksheets;

use App\Repositories\Contracts\WorksheetRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListWorksheetsAction
{
    public function __construct(
        private readonly WorksheetRepositoryInterface $repository,
    ) {}

    public function execute(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
