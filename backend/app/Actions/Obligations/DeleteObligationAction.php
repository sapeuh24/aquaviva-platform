<?php

namespace App\Actions\Obligations;

use App\Models\Obligation;
use App\Repositories\Contracts\ObligationRepositoryInterface;

class DeleteObligationAction
{
    public function __construct(
        private readonly ObligationRepositoryInterface $repository,
    ) {}

    public function execute(Obligation $obligation): void
    {
        $this->repository->softDelete($obligation);
    }
}
