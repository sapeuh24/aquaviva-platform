<?php

namespace App\Actions\Programs;

use App\Repositories\Contracts\ProgramRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListProgramsAction
{
    public function __construct(
        private readonly ProgramRepositoryInterface $repository,
    ) {}

    public function execute(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
