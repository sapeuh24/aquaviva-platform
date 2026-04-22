<?php

namespace App\Repositories;

use App\Models\Alert;
use App\Repositories\Contracts\AlertRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AlertRepository implements AlertRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return Alert::query()
            ->with(['indicator'])
            ->when(
                isset($filters['status']),
                fn ($q) => $q->where('status', $filters['status']),
            )
            ->when(
                isset($filters['type']),
                fn ($q) => $q->where('type', $filters['type']),
            )
            ->when(
                isset($filters['indicator_id']),
                fn ($q) => $q->where('indicator_id', $filters['indicator_id']),
            )
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Alert
    {
        return Alert::with(['indicator'])->findOrFail($id);
    }

    public function markAsRead(Alert $alert): Alert
    {
        $alert->update([
            'status'  => 'leida',
            'read_at' => now(),
        ]);

        return $alert->fresh();
    }

    public function countUnread(int $companyId): int
    {
        return Alert::where('company_id', $companyId)
            ->whereNull('read_at')
            ->count();
    }
}
