<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\Indicators\IndicatorData;
use App\Models\Indicator;
use Illuminate\Pagination\LengthAwarePaginator;

interface IndicatorRepositoryInterface
{
    public function paginate(int $perPage, array $filters): LengthAwarePaginator;

    public function findOrFail(int $id): Indicator;

    public function create(IndicatorData $data): Indicator;

    public function update(Indicator $indicator, IndicatorData $data): Indicator;

    public function softDelete(Indicator $indicator): void;
}
