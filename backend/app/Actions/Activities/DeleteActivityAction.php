<?php

namespace App\Actions\Activities;

use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;

class DeleteActivityAction
{
    public function __construct(
        private readonly ActivityRepositoryInterface $repository,
    ) {}

    public function execute(Activity $activity): void
    {
        $this->repository->softDelete($activity);
    }
}
