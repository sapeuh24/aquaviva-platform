<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\Obligations\ObligationData;
use App\Models\Obligation;
use Illuminate\Pagination\LengthAwarePaginator;

interface ObligationRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Obligation;

    public function create(ObligationData $data): Obligation;

    public function update(Obligation $obligation, ObligationData $data): Obligation;

    public function softDelete(Obligation $obligation): void;
}
