<?php

namespace App\Actions\Indicators;

use App\DataTransferObjects\Indicators\IndicatorData;
use App\Models\Indicator;
use App\Repositories\Contracts\IndicatorRepositoryInterface;

class UpdateIndicatorAction
{
    public function __construct(
        private readonly IndicatorRepositoryInterface $repository,
    ) {}

    public function execute(Indicator $indicator, IndicatorData $data): Indicator
    {
        return $this->repository->update($indicator, $data);
    }
}
