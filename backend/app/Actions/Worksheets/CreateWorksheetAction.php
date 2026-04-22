<?php

namespace App\Actions\Worksheets;

use App\DataTransferObjects\Worksheets\WorksheetData;
use App\Models\Worksheet;
use App\Repositories\Contracts\WorksheetRepositoryInterface;

class CreateWorksheetAction
{
    public function __construct(
        private readonly WorksheetRepositoryInterface $repository,
    ) {}

    public function execute(WorksheetData $data): Worksheet
    {
        return $this->repository->create($data);
    }
}
