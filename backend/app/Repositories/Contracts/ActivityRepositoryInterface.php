<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\Activities\ActivityData;
use App\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;

interface ActivityRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Activity;

    public function create(ActivityData $data): Activity;

    public function update(Activity $activity, ActivityData $data): Activity;

    public function softDelete(Activity $activity): void;
}
