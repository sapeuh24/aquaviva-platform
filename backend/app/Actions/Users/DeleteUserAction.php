<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class DeleteUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
    ) {}

    public function execute(User $user): void
    {
        $this->repository->softDelete($user);
    }
}
