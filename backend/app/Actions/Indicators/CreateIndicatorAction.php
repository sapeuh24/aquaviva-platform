<?php

namespace App\Actions\Indicators;

use App\DataTransferObjects\Indicators\IndicatorData;
use App\Models\Indicator;
use App\Repositories\Contracts\IndicatorRepositoryInterface;

class CreateIndicatorAction
{
    public function __construct(
        private readonly IndicatorRepositoryInterface $repository,
    ) {}

    public function execute(IndicatorData $data): Indicator
    {
        return $this->repository->create($data);
    }
}
