<?php

namespace App\Repositories;

use App\DataTransferObjects\Worksheets\WorksheetData;
use App\Models\Worksheet;
use App\Repositories\Contracts\WorksheetRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class WorksheetRepository implements WorksheetRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return Worksheet::query()
            ->with(['obligation'])
            ->withCount('indicators')
            ->when(
                isset($filters['obligation_id']),
                fn ($q) => $q->where('obligation_id', $filters['obligation_id']),
            )
            ->when(
                isset($filters['monitoring_id']),
                fn ($q) => $q->where('monitoring_id', $filters['monitoring_id']),
            )
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Worksheet
    {
        return Worksheet::with(['obligation'])->withCount('indicators')->findOrFail($id);
    }

    public function create(WorksheetData $data): Worksheet
    {
        return Worksheet::create([
            'company_id'       => $data->company_id,
            'obligation_id'    => $data->obligation_id,
            'monitoring_id'    => $data->monitoring_id,
            'monitoring_tool'  => $data->monitoring_tool,
            'monitoring_phase' => $data->monitoring_phase,
            'name'             => $data->name,
            'objective'        => $data->objective,
            'target'           => $data->target,
            'observations'     => $data->observations,
        ]);
    }

    public function update(Worksheet $worksheet, WorksheetData $data): Worksheet
    {
        $worksheet->update([
            'company_id'       => $data->company_id,
            'obligation_id'    => $data->obligation_id,
            'monitoring_id'    => $data->monitoring_id,
            'monitoring_tool'  => $data->monitoring_tool,
            'monitoring_phase' => $data->monitoring_phase,
            'name'             => $data->name,
            'objective'        => $data->objective,
            'target'           => $data->target,
            'observations'     => $data->observations,
        ]);

        return $worksheet->fresh(['obligation']);
    }

    public function softDelete(Worksheet $worksheet): void
    {
        $worksheet->delete();
    }
}
