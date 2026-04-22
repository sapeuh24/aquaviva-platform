<?php

namespace App\Actions\Worksheets;

use App\DataTransferObjects\Worksheets\WorksheetData;
use App\Models\Worksheet;
use App\Repositories\Contracts\WorksheetRepositoryInterface;

class UpdateWorksheetAction
{
    public function __construct(
        private readonly WorksheetRepositoryInterface $repository,
    ) {}

    public function execute(Worksheet $worksheet, WorksheetData $data): Worksheet
    {
        return $this->repository->update($worksheet, $data);
    }
}
