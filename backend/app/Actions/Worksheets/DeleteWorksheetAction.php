<?php

namespace App\Actions\Worksheets;

use App\Models\Worksheet;
use App\Repositories\Contracts\WorksheetRepositoryInterface;

class DeleteWorksheetAction
{
    public function __construct(
        private readonly WorksheetRepositoryInterface $repository,
    ) {}

    public function execute(Worksheet $worksheet): void
    {
        $this->repository->softDelete($worksheet);
    }
}
