<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\Companies\CompanyData;
use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Company;

    public function create(CompanyData $data): Company;

    public function update(Company $company, CompanyData $data): Company;

    public function softDelete(Company $company): void;
}
