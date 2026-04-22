<?php

namespace App\Repositories;

use App\DataTransferObjects\Evidences\EvidenceData;
use App\Models\Evidence;
use App\Repositories\Contracts\EvidenceRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class EvidenceRepository implements EvidenceRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator
    {
        return Evidence::query()
            ->with(['activity', 'uploader'])
            ->when(
                isset($filters['activity_id']),
                fn ($q) => $q->where('activity_id', $filters['activity_id']),
            )
            ->when(
                isset($filters['uploaded_by']),
                fn ($q) => $q->where('uploaded_by', $filters['uploaded_by']),
            )
            ->when(
                isset($filters['mime_type']),
                fn ($q) => match ($filters['mime_type']) {
                    'pdf'      => $q->where('mime_type', 'application/pdf'),
                    'image'    => $q->where('mime_type', 'like', 'image/%'),
                    'video'    => $q->where('mime_type', 'like', 'video/%'),
                    'document' => $q->whereIn('mime_type', [
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]),
                    default => $q,
                },
            )
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findOrFail(int $id): Evidence
    {
        return Evidence::with(['uploader', 'activity'])->findOrFail($id);
    }

    public function create(EvidenceData $data): Evidence
    {
        return Evidence::create([
            'company_id'    => $data->company_id,
            'activity_id'   => $data->activity_id,
            'uploaded_by'   => $data->uploaded_by,
            'original_name' => $data->original_name,
            'storage_path'  => $data->storage_path,
            'mime_type'     => $data->mime_type,
            'size_bytes'    => $data->size_bytes,
            'description'   => $data->description,
        ]);
    }

    public function softDelete(Evidence $evidence): void
    {
        $evidence->delete();
    }

    public function purgeOlderThan(int $years): int
    {
        return Evidence::withTrashed()
            ->where('created_at', '<=', Carbon::now()->subYears($years))
            ->count();
    }
}
