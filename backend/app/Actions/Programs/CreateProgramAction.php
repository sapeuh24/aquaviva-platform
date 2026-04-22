<?php

namespace App\Actions\Programs;

use App\DataTransferObjects\Programs\ProgramData;
use App\Models\Program;
use App\Repositories\Contracts\ProgramRepositoryInterface;

class CreateProgramAction
{
    public function __construct(
        private readonly ProgramRepositoryInterface $repository,
    ) {}

    public function execute(ProgramData $data): Program
    {
        return $this->repository->create($data);
    }
}
