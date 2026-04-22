<?php

namespace App\Actions\Alerts;

use App\Models\Alert;
use App\Repositories\Contracts\AlertRepositoryInterface;

class DismissAlertAction
{
    public function __construct(
        private readonly AlertRepositoryInterface $repository,
    ) {}

    public function execute(Alert $alert): Alert
    {
        return $this->repository->markAsRead($alert);
    }
}
