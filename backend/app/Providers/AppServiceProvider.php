<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Alert;
use App\Models\Company;
use App\Models\Evidence;
use App\Models\Indicator;
use App\Models\Obligation;
use App\Models\OrganizationProject;
use App\Models\Program;
use App\Models\User;
use App\Models\Worksheet;
use App\Policies\ActivityPolicy;
use App\Policies\AlertPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\EvidencePolicy;
use App\Policies\IndicatorPolicy;
use App\Policies\ObligationPolicy;
use App\Policies\OrganizationProjectPolicy;
use App\Policies\ProgramPolicy;
use App\Policies\UserPolicy;
use App\Policies\WorksheetPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            if ($user->hasRole('super_admin')) {
                return true;
            }

            return null;
        });

        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Program::class, ProgramPolicy::class);
        Gate::policy(OrganizationProject::class, OrganizationProjectPolicy::class);
        Gate::policy(Obligation::class, ObligationPolicy::class);
        Gate::policy(Worksheet::class, WorksheetPolicy::class);
        Gate::policy(Indicator::class, IndicatorPolicy::class);
        Gate::policy(Activity::class, ActivityPolicy::class);
        Gate::policy(Evidence::class, EvidencePolicy::class);
        Gate::policy(Alert::class, AlertPolicy::class);
    }
}
