<?php

namespace App\Actions\Activities;

use App\DataTransferObjects\Activities\ActivityData;
use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;

class CreateActivityAction
{
    public function __construct(
        private readonly ActivityRepositoryInterface $repository,
    ) {}

    public function execute(ActivityData $data): Activity
    {
        return $this->repository->create($data);
    }
}
