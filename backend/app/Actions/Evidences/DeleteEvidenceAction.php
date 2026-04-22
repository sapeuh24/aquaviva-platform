<?php

namespace App\Actions\Evidences;

use App\Exceptions\BusinessException;
use App\Models\Evidence;
use App\Models\User;
use App\Repositories\Contracts\EvidenceRepositoryInterface;
use App\Services\EvidenceUploadService;

class DeleteEvidenceAction
{
    public function __construct(
        private readonly EvidenceRepositoryInterface $repository,
        private readonly EvidenceUploadService $uploadService,
    ) {}

    public function execute(Evidence $evidence, User $user): void
    {
        if ($user->id !== $evidence->uploaded_by) {
            throw new BusinessException('Solo puedes eliminar archivos que tu has subido.', 403);
        }

        $this->uploadService->delete($evidence);
        $this->repository->softDelete($evidence);
    }
}
