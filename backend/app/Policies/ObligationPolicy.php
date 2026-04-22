<?php

namespace App\Policies;

use App\Models\Obligation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ObligationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['coordinator', 'analyst', 'viewer', 'admin']);
    }

    public function view(User $user, Obligation $obligation): Response
    {
        if (! $user->hasAnyRole(['coordinator', 'analyst', 'viewer', 'admin'])) {
            return Response::deny('No tienes permiso para ver obligaciones.');
        }

        if ($user->company_id !== $obligation->company_id) {
            return Response::deny('No puedes acceder a obligaciones de otra empresa.');
        }

        return Response::allow();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'coordinator']);
    }

    public function update(User $user, Obligation $obligation): Response
    {
        if (! $user->hasAnyRole(['admin', 'coordinator'])) {
            return Response::deny('No tienes permiso para actualizar obligaciones.');
        }

        if ($user->company_id !== $obligation->company_id) {
            return Response::deny('No puedes modificar obligaciones de otra empresa.');
        }

        return Response::allow();
    }

    public function delete(User $user, Obligation $obligation): Response
    {
        if (! $user->hasRole('admin')) {
            return Response::deny('Solo los administradores pueden eliminar obligaciones.');
        }

        if ($user->company_id !== $obligation->company_id) {
            return Response::deny('No puedes eliminar obligaciones de otra empresa.');
        }

        return Response::allow();
    }
}
