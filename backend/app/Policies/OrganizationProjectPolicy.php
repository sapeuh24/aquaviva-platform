<?php

namespace App\Policies;

use App\Models\OrganizationProject;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['coordinator', 'analyst', 'viewer', 'admin']);
    }

    public function view(User $user, OrganizationProject $project): Response
    {
        if (! $user->hasAnyRole(['coordinator', 'analyst', 'viewer', 'admin'])) {
            return Response::deny('No tienes permiso para ver proyectos.');
        }

        if ($user->company_id !== $project->company_id) {
            return Response::deny('No puedes acceder a proyectos de otra empresa.');
        }

        return Response::allow();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'coordinator']);
    }

    public function update(User $user, OrganizationProject $project): Response
    {
        if (! $user->hasAnyRole(['admin', 'coordinator'])) {
            return Response::deny('No tienes permiso para actualizar proyectos.');
        }

        if ($user->company_id !== $project->company_id) {
            return Response::deny('No puedes modificar proyectos de otra empresa.');
        }

        return Response::allow();
    }

    public function delete(User $user, OrganizationProject $project): Response
    {
        if (! $user->hasRole('admin')) {
            return Response::deny('Solo los administradores pueden eliminar proyectos.');
        }

        if ($user->company_id !== $project->company_id) {
            return Response::deny('No puedes eliminar proyectos de otra empresa.');
        }

        return Response::allow();
    }

    public function attachObligation(User $user, OrganizationProject $project): Response
    {
        if (! $user->hasAnyRole(['admin', 'coordinator'])) {
            return Response::deny('No tienes permiso para asociar obligaciones a este proyecto.');
        }

        if ($user->company_id !== $project->company_id) {
            return Response::deny('No puedes modificar proyectos de otra empresa.');
        }

        return Response::allow();
    }

    public function detachObligation(User $user, OrganizationProject $project): Response
    {
        if (! $user->hasAnyRole(['admin', 'coordinator'])) {
            return Response::deny('No tienes permiso para desasociar obligaciones de este proyecto.');
        }

        if ($user->company_id !== $project->company_id) {
            return Response::deny('No puedes modificar proyectos de otra empresa.');
        }

        return Response::allow();
    }
}
