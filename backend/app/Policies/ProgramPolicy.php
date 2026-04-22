<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['coordinator', 'analyst', 'viewer', 'admin']);
    }

    public function view(User $user, Program $program): Response
    {
        if (! $user->hasAnyRole(['coordinator', 'analyst', 'viewer', 'admin'])) {
            return Response::deny('No tienes permiso para ver programas.');
        }

        if ($user->company_id !== $program->company_id) {
            return Response::deny('No puedes acceder a programas de otra empresa.');
        }

        return Response::allow();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'coordinator']);
    }

    public function update(User $user, Program $program): Response
    {
        if (! $user->hasAnyRole(['admin', 'coordinator'])) {
            return Response::deny('No tienes permiso para actualizar programas.');
        }

        if ($user->company_id !== $program->company_id) {
            return Response::deny('No puedes modificar programas de otra empresa.');
        }

        return Response::allow();
    }

    public function delete(User $user, Program $program): Response
    {
        if (! $user->hasRole('admin')) {
            return Response::deny('Solo los administradores pueden eliminar programas.');
        }

        if ($user->company_id !== $program->company_id) {
            return Response::deny('No puedes eliminar programas de otra empresa.');
        }

        return Response::allow();
    }
}
