<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer']);
    }

    public function view(User $user, Activity $activity): Response
    {
        if (! $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer'])) {
            return Response::deny('No tienes permiso para ver actividades.');
        }

        if ($user->company_id !== $activity->company_id) {
            return Response::deny('No puedes acceder a actividades de otra empresa.');
        }

        return Response::allow();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['coordinator', 'admin']);
    }

    public function update(User $user, Activity $activity): Response
    {
        if ($user->company_id !== $activity->company_id) {
            return Response::deny('No puedes modificar actividades de otra empresa.');
        }

        if ($user->hasAnyRole(['coordinator', 'admin'])) {
            return Response::allow();
        }

        if ($user->hasRole('analyst') && $activity->users->contains('id', $user->id)) {
            return Response::allow();
        }

        return Response::deny('No tienes permiso para actualizar esta actividad.');
    }

    public function delete(User $user, Activity $activity): Response
    {
        if (! $user->hasAnyRole(['coordinator', 'admin'])) {
            return Response::deny('No tienes permiso para eliminar actividades.');
        }

        if ($user->company_id !== $activity->company_id) {
            return Response::deny('No puedes eliminar actividades de otra empresa.');
        }

        return Response::allow();
    }

    public function assignUser(User $user, Activity $activity): Response
    {
        if (! $user->hasAnyRole(['coordinator', 'admin'])) {
            return Response::deny('No tienes permiso para asignar usuarios a actividades.');
        }

        if ($user->company_id !== $activity->company_id) {
            return Response::deny('No puedes gestionar actividades de otra empresa.');
        }

        return Response::allow();
    }

    public function unassignUser(User $user, Activity $activity): Response
    {
        if (! $user->hasAnyRole(['coordinator', 'admin'])) {
            return Response::deny('No tienes permiso para desasignar usuarios de actividades.');
        }

        if ($user->company_id !== $activity->company_id) {
            return Response::deny('No puedes gestionar actividades de otra empresa.');
        }

        return Response::allow();
    }
}
