<?php

namespace App\Actions\OrganizationProjects;

use App\DataTransferObjects\OrganizationProjects\OrganizationProjectData;
use App\Models\OrganizationProject;
use App\Repositories\Contracts\OrganizationProjectRepositoryInterface;

class UpdateOrganizationProjectAction
{
    public function __construct(
        private readonly OrganizationProjectRepositoryInterface $repository,
    ) {}

    public function execute(OrganizationProject $project, OrganizationProjectData $data): OrganizationProject
    {
        return $this->repository->update($project, $data);
    }
}
