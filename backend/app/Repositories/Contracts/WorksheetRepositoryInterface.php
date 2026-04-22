<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\Worksheets\WorksheetData;
use App\Models\Worksheet;
use Illuminate\Pagination\LengthAwarePaginator;

interface WorksheetRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Worksheet;

    public function create(WorksheetData $data): Worksheet;

    public function update(Worksheet $worksheet, WorksheetData $data): Worksheet;

    public function softDelete(Worksheet $worksheet): void;
}
