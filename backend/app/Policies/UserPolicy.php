<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'coordinator', 'analyst', 'viewer']);
    }

    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id
            || $user->company_id === $model->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'coordinator', 'analyst', 'viewer']);
    }

    public function update(User $user, User $model): Response
    {
        if ($user->id === $model->id) {
            return Response::allow();
        }

        if ($user->hasRole('admin') && $user->company_id === $model->company_id) {
            return Response::allow();
        }

        return Response::deny('No tienes permiso para modificar este usuario.');
    }

    public function delete(User $user, User $model): Response
    {
        if ($user->id === $model->id) {
            return Response::deny('No puedes eliminarte a ti mismo.');
        }

        if ($user->hasRole('admin') && $user->company_id === $model->company_id) {
            return Response::allow();
        }

        return Response::deny('No tienes permiso para eliminar este usuario.');
    }
}
