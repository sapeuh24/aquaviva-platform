<?php

namespace App\Actions\Programs;

use App\DataTransferObjects\Programs\ProgramData;
use App\Models\Program;
use App\Repositories\Contracts\ProgramRepositoryInterface;

class UpdateProgramAction
{
    public function __construct(
        private readonly ProgramRepositoryInterface $repository,
    ) {}

    public function execute(Program $program, ProgramData $data): Program
    {
        return $this->repository->update($program, $data);
    }
}
