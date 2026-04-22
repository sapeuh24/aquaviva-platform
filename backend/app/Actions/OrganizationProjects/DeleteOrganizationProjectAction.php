<?php

namespace App\Actions\OrganizationProjects;

use App\Models\OrganizationProject;
use App\Repositories\Contracts\OrganizationProjectRepositoryInterface;

class DeleteOrganizationProjectAction
{
    public function __construct(
        private readonly OrganizationProjectRepositoryInterface $repository,
    ) {}

    public function execute(OrganizationProject $project): void
    {
        $this->repository->softDelete($project);
    }
}
