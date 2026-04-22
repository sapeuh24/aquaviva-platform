<?php

namespace App\Repositories\Contracts;

use App\Models\Alert;
use Illuminate\Pagination\LengthAwarePaginator;

interface AlertRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Alert;

    public function markAsRead(Alert $alert): Alert;

    public function countUnread(int $companyId): int;
}
