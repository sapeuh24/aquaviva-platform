<?php

namespace App\Actions\Users;

use App\DataTransferObjects\Users\UserData;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class CreateUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
    ) {}

    public function execute(UserData $data): User
    {
        return $this->repository->create($data);
    }
}
