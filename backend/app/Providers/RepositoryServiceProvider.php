<?php

namespace App\Providers;

use App\Repositories\ActivityRepository;
use App\Repositories\AlertRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\Contracts\ActivityRepositoryInterface;
use App\Repositories\Contracts\AlertRepositoryInterface;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Contracts\EvidenceRepositoryInterface;
use App\Repositories\Contracts\IndicatorRepositoryInterface;
use App\Repositories\Contracts\ObligationRepositoryInterface;
use App\Repositories\Contracts\OrganizationProjectRepositoryInterface;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WorksheetRepositoryInterface;
use App\Repositories\EvidenceRepository;
use App\Repositories\IndicatorRepository;
use App\Repositories\ObligationRepository;
use App\Repositories\OrganizationProjectRepository;
use App\Repositories\ProgramRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorksheetRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProgramRepositoryInterface::class, ProgramRepository::class);
        $this->app->bind(OrganizationProjectRepositoryInterface::class, OrganizationProjectRepository::class);
        $this->app->bind(ObligationRepositoryInterface::class, ObligationRepository::class);
        $this->app->bind(WorksheetRepositoryInterface::class, WorksheetRepository::class);
        $this->app->bind(IndicatorRepositoryInterface::class, IndicatorRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(EvidenceRepositoryInterface::class, EvidenceRepository::class);
        $this->app->bind(AlertRepositoryInterface::class, AlertRepository::class);
    }

    public function boot(): void {}
}
