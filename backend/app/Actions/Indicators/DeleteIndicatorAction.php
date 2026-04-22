<?php

namespace App\Actions\Indicators;

use App\Models\Indicator;
use App\Repositories\Contracts\IndicatorRepositoryInterface;

class DeleteIndicatorAction
{
    public function __construct(
        private readonly IndicatorRepositoryInterface $repository,
    ) {}

    public function execute(Indicator $indicator): void
    {
        $this->repository->softDelete($indicator);
    }
}
