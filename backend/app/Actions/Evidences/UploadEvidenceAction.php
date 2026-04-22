<?php

namespace App\Actions\Evidences;

use App\DataTransferObjects\Evidences\EvidenceData;
use App\Http\Requests\Evidences\StoreEvidenceRequest;
use App\Models\Evidence;
use App\Repositories\Contracts\EvidenceRepositoryInterface;
use App\Services\EvidenceUploadService;

class UploadEvidenceAction
{
    public function __construct(
        private readonly EvidenceRepositoryInterface $repository,
        private readonly EvidenceUploadService $uploadService,
    ) {}

    public function execute(StoreEvidenceRequest $request): Evidence
    {
        $file       = $request->file('file');
        $activityId = (int) $request->validated()['activity_id'];

        $fileData = $this->uploadService->store($file, $activityId);

        $evidenceData = EvidenceData::fromRequest([
            'company_id'    => auth()->user()->company_id,
            'activity_id'   => $activityId,
            'uploaded_by'   => auth()->id(),
            'original_name' => $fileData['original_name'],
            'storage_path'  => $fileData['storage_path'],
            'mime_type'     => $fileData['mime_type'],
            'size_bytes'    => $fileData['size_bytes'],
            'description'   => $request->validated()['description'] ?? null,
        ]);

        return $this->repository->create($evidenceData);
    }
}
