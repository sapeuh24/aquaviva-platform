<?php

namespace App\Actions\Users;

use App\DataTransferObjects\Users\UserData;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UpdateUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
    ) {}

    public function execute(User $user, UserData $data): User
    {
        return $this->repository->update($user, $data);
    }
}
