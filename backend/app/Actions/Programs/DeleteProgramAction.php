<?php

namespace App\Actions\Programs;

use App\Models\Program;
use App\Repositories\Contracts\ProgramRepositoryInterface;

class DeleteProgramAction
{
    public function __construct(
        private readonly ProgramRepositoryInterface $repository,
    ) {}

    public function execute(Program $program): void
    {
        $this->repository->softDelete($program);
    }
}
