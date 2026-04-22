<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\OrganizationProjects\OrganizationProjectData;
use App\Models\OrganizationProject;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrganizationProjectRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): OrganizationProject;

    public function create(OrganizationProjectData $data): OrganizationProject;

    public function update(OrganizationProject $project, OrganizationProjectData $data): OrganizationProject;

    public function softDelete(OrganizationProject $project): void;
}
