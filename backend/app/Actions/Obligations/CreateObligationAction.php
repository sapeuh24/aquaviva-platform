<?php

namespace App\Actions\Obligations;

use App\DataTransferObjects\Obligations\ObligationData;
use App\Models\Obligation;
use App\Repositories\Contracts\ObligationRepositoryInterface;

class CreateObligationAction
{
    public function __construct(
        private readonly ObligationRepositoryInterface $repository,
    ) {}

    public function execute(ObligationData $data): Obligation
    {
        return $this->repository->create($data);
    }
}
