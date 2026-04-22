<?php

namespace App\Actions\Companies;

use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;

class DeleteCompanyAction
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {}

    public function execute(Company $company): void
    {
        $this->repository->softDelete($company);
    }
}
