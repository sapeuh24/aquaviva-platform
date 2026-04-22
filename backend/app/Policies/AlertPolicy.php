<?php

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AlertPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer']);
    }

    public function view(User $user, Alert $alert): Response
    {
        if (! $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer'])) {
            return Response::deny('No tienes permiso para ver alertas.');
        }

        if ($user->company_id !== $alert->company_id) {
            return Response::deny('No puedes acceder a alertas de otra empresa.');
        }

        return Response::allow();
    }

    public function dismiss(User $user, Alert $alert): Response
    {
        if (! $user->hasAnyRole(['analyst', 'coordinator', 'admin'])) {
            return Response::deny('No tienes permiso para desestimar alertas.');
        }

        if ($user->company_id !== $alert->company_id) {
            return Response::deny('No puedes gestionar alertas de otra empresa.');
        }

        return Response::allow();
    }
}
