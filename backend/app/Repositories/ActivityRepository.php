<?php

namespace App\Repositories;

use App\DataTransferObjects\Activities\ActivityData;
use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return Activity::query()
            ->with(['indicator', 'users'])
            ->withCount('evidences')
            ->when(
                isset($filters['indicator_id']),
                fn ($q) => $q->where('indicator_id', $filters['indicator_id']),
            )
            ->when(
                isset($filters['compliance_status']),
                fn ($q) => $q->where('compliance_status', $filters['compliance_status']),
            )
            ->when(
                isset($filters['scheduled_from']),
                fn ($q) => $q->where('scheduled_date', '>=', $filters['scheduled_from']),
            )
            ->when(
                isset($filters['scheduled_to']),
                fn ($q) => $q->where('scheduled_date', '<=', $filters['scheduled_to']),
            )
            ->orderBy('scheduled_date', 'desc')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Activity
    {
        return Activity::with(['indicator', 'users'])->withCount('evidences')->findOrFail($id);
    }

    public function create(ActivityData $data): Activity
    {
        return Activity::create([
            'company_id'        => $data->company_id,
            'indicator_id'      => $data->indicator_id,
            'name'              => $data->name,
            'description'       => $data->description,
            'scheduled_date'    => $data->scheduled_date,
            'executed_date'     => $data->executed_date,
            'compliance_status' => $data->compliance_status,
            'notes'             => $data->notes,
        ]);
    }

    public function update(Activity $activity, ActivityData $data): Activity
    {
        $activity->update([
            'indicator_id'      => $data->indicator_id,
            'name'              => $data->name,
            'description'       => $data->description,
            'scheduled_date'    => $data->scheduled_date,
            'executed_date'     => $data->executed_date,
            'compliance_status' => $data->compliance_status,
            'notes'             => $data->notes,
        ]);

        return $activity->fresh(['indicator', 'users']);
    }

    public function softDelete(Activity $activity): void
    {
        $activity->delete();
    }
}
