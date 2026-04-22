<?php

namespace App\Actions\Companies;

use App\DataTransferObjects\Companies\CompanyData;
use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;

class UpdateCompanyAction
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {}

    public function execute(Company $company, CompanyData $data): Company
    {
        return $this->repository->update($company, $data);
    }
}
