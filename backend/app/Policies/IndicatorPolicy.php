<?php

namespace App\Policies;

use App\Models\Indicator;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IndicatorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer']);
    }

    public function view(User $user, Indicator $indicator): Response
    {
        if (! $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer'])) {
            return Response::deny('No tienes permiso para ver indicadores.');
        }

        if ($user->company_id !== $indicator->company_id) {
            return Response::deny('No puedes acceder a indicadores de otra empresa.');
        }

        return Response::allow();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['coordinator', 'admin']);
    }

    public function update(User $user, Indicator $indicator): Response
    {
        if (! $user->hasAnyRole(['coordinator', 'admin'])) {
            return Response::deny('No tienes permiso para actualizar indicadores.');
        }

        if ($user->company_id !== $indicator->company_id) {
            return Response::deny('No puedes modificar indicadores de otra empresa.');
        }

        return Response::allow();
    }

    public function delete(User $user, Indicator $indicator): Response
    {
        if (! $user->hasRole('admin')) {
            return Response::deny('Solo los administradores pueden eliminar indicadores.');
        }

        if ($user->company_id !== $indicator->company_id) {
            return Response::deny('No puedes eliminar indicadores de otra empresa.');
        }

        return Response::allow();
    }
}
