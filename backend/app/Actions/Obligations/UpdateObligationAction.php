<?php

namespace App\Actions\Obligations;

use App\DataTransferObjects\Obligations\ObligationData;
use App\Models\Obligation;
use App\Repositories\Contracts\ObligationRepositoryInterface;

class UpdateObligationAction
{
    public function __construct(
        private readonly ObligationRepositoryInterface $repository,
    ) {}

    public function execute(Obligation $obligation, ObligationData $data): Obligation
    {
        return $this->repository->update($obligation, $data);
    }
}
