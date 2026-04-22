<?php

namespace App\Repositories;

use App\DataTransferObjects\OrganizationProjects\OrganizationProjectData;
use App\Models\OrganizationProject;
use App\Repositories\Contracts\OrganizationProjectRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationProjectRepository implements OrganizationProjectRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return OrganizationProject::query()
            ->with(['municipality.department'])
            ->withCount('obligations')
            ->when(
                isset($filters['search']),
                fn ($q) => $q->where(function ($q) use ($filters): void {
                    $q->where('name', 'like', "%{$filters['search']}%")
                        ->orWhere('code', 'like', "%{$filters['search']}%");
                }),
            )
            ->when(
                isset($filters['status']),
                fn ($q) => $q->where('status', $filters['status']),
            )
            ->when(
                isset($filters['municipality_id']),
                fn ($q) => $q->where('municipality_id', $filters['municipality_id']),
            )
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): OrganizationProject
    {
        return OrganizationProject::with(['municipality.department'])
            ->withCount('obligations')
            ->findOrFail($id);
    }

    public function create(OrganizationProjectData $data): OrganizationProject
    {
        return OrganizationProject::create([
            'company_id'      => $data->company_id,
            'municipality_id' => $data->municipality_id,
            'name'            => $data->name,
            'code'            => $data->code,
            'description'     => $data->description,
            'location'        => $data->location,
            'start_date'      => $data->start_date,
            'end_date'        => $data->end_date,
            'status'          => $data->status,
        ]);
    }

    public function update(OrganizationProject $project, OrganizationProjectData $data): OrganizationProject
    {
        $project->update([
            'company_id'      => $data->company_id,
            'municipality_id' => $data->municipality_id,
            'name'            => $data->name,
            'code'            => $data->code,
            'description'     => $data->description,
            'location'        => $data->location,
            'start_date'      => $data->start_date,
            'end_date'        => $data->end_date,
            'status'          => $data->status,
        ]);

        return $project->fresh(['municipality.department']);
    }

    public function softDelete(OrganizationProject $project): void
    {
        $project->delete();
    }
}
