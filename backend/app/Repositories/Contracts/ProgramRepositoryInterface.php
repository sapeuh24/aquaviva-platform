<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\Programs\ProgramData;
use App\Models\Program;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProgramRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Program;

    public function create(ProgramData $data): Program;

    public function update(Program $program, ProgramData $data): Program;

    public function softDelete(Program $program): void;
}
