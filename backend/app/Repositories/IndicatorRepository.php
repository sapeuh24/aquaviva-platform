<?php

namespace App\Repositories;

use App\DataTransferObjects\Indicators\IndicatorData;
use App\Models\Indicator;
use App\Repositories\Contracts\IndicatorRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class IndicatorRepository implements IndicatorRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return Indicator::query()
            ->with(['indicatorFrequency'])
            ->withCount('activities')
            ->when(
                isset($filters['worksheet_id']),
                fn ($q) => $q->where('worksheet_id', $filters['worksheet_id']),
            )
            ->when(
                isset($filters['is_active']),
                fn ($q) => $q->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN)),
            )
            ->when(
                isset($filters['overdue']) && filter_var($filters['overdue'], FILTER_VALIDATE_BOOLEAN),
                fn ($q) => $q->where('next_due_date', '<', Carbon::today()),
            )
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Indicator
    {
        return Indicator::with(['indicatorFrequency'])->withCount('activities')->findOrFail($id);
    }

    public function create(IndicatorData $data): Indicator
    {
        return Indicator::create([
            'company_id'             => $data->company_id,
            'worksheet_id'           => $data->worksheet_id,
            'indicator_frequency_id' => $data->indicator_frequency_id,
            'name'                   => $data->name,
            'objective'              => $data->objective,
            'target'                 => $data->target,
            'next_due_date'          => $data->next_due_date,
            'is_active'              => $data->is_active,
        ]);
    }

    public function update(Indicator $indicator, IndicatorData $data): Indicator
    {
        $indicator->update([
            'worksheet_id'           => $data->worksheet_id,
            'indicator_frequency_id' => $data->indicator_frequency_id,
            'name'                   => $data->name,
            'objective'              => $data->objective,
            'target'                 => $data->target,
            'next_due_date'          => $data->next_due_date,
            'is_active'              => $data->is_active,
        ]);

        return $indicator->fresh(['indicatorFrequency']);
    }

    public function softDelete(Indicator $indicator): void
    {
        $indicator->delete();
    }
}
