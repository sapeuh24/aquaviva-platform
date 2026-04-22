<?php

namespace App\Actions\Activities;

use App\DataTransferObjects\Activities\ActivityData;
use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;

class UpdateActivityAction
{
    public function __construct(
        private readonly ActivityRepositoryInterface $repository,
    ) {}

    public function execute(Activity $activity, ActivityData $data): Activity
    {
        return $this->repository->update($activity, $data);
    }
}
