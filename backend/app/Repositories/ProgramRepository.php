<?php

namespace App\Repositories;

use App\DataTransferObjects\Programs\ProgramData;
use App\Models\Program;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProgramRepository implements ProgramRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return Program::query()
            ->with(['environmentalMedium'])
            ->withCount('projects')
            ->when(
                isset($filters['search']),
                fn ($q) => $q->where(function ($q) use ($filters): void {
                    $q->where('name', 'like', "%{$filters['search']}%")
                        ->orWhere('code', 'like', "%{$filters['search']}%");
                }),
            )
            ->when(
                isset($filters['company_id']),
                fn ($q) => $q->where('company_id', $filters['company_id']),
            )
            ->when(
                isset($filters['environmental_medium_id']),
                fn ($q) => $q->where('environmental_medium_id', $filters['environmental_medium_id']),
            )
            ->when(
                isset($filters['is_active']),
                fn ($q) => $q->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN)),
            )
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Program
    {
        return Program::with(['environmentalMedium'])->withCount('projects')->findOrFail($id);
    }

    public function create(ProgramData $data): Program
    {
        return Program::create([
            'company_id'               => $data->company_id,
            'environmental_medium_id'  => $data->environmental_medium_id,
            'name'                     => $data->name,
            'code'                     => $data->code,
            'description'              => $data->description,
            'is_active'                => $data->is_active,
        ]);
    }

    public function update(Program $program, ProgramData $data): Program
    {
        $program->update([
            'company_id'               => $data->company_id,
            'environmental_medium_id'  => $data->environmental_medium_id,
            'name'                     => $data->name,
            'code'                     => $data->code,
            'description'              => $data->description,
            'is_active'                => $data->is_active,
        ]);

        return $program->fresh(['environmentalMedium']);
    }

    public function softDelete(Program $program): void
    {
        $program->delete();
    }
}
