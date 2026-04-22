<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\Users\UserData;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): User;

    public function create(UserData $data): User;

    public function update(User $user, UserData $data): User;

    public function softDelete(User $user): void;
}
