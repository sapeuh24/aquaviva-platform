<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'coordinator', 'analyst', 'viewer']);
    }

    public function view(User $user, Company $company): bool
    {
        return $user->company_id === $company->id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Company $company): Response
    {
        if ($user->hasRole('admin') && $user->company_id === $company->id) {
            return Response::allow();
        }

        return Response::deny('No tienes permiso para actualizar esta empresa.');
    }

    public function delete(User $user, Company $company): bool
    {
        return false;
    }
}
