<?php

namespace App\Actions\OrganizationProjects;

use App\DataTransferObjects\OrganizationProjects\OrganizationProjectData;
use App\Models\OrganizationProject;
use App\Repositories\Contracts\OrganizationProjectRepositoryInterface;

class CreateOrganizationProjectAction
{
    public function __construct(
        private readonly OrganizationProjectRepositoryInterface $repository,
    ) {}

    public function execute(OrganizationProjectData $data): OrganizationProject
    {
        return $this->repository->create($data);
    }
}
