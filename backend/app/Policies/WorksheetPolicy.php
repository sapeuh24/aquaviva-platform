<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Worksheet;
use Illuminate\Auth\Access\Response;

class WorksheetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer']);
    }

    /**
     * Worksheet no tiene company_id propio en la tabla; se obtiene via obligation.
     */
    public function view(User $user, Worksheet $worksheet): Response
    {
        if (! $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer'])) {
            return Response::deny('No tienes permiso para ver fichas de trabajo.');
        }

        if ($user->company_id !== $this->resolveCompanyId($worksheet)) {
            return Response::deny('No puedes acceder a fichas de trabajo de otra empresa.');
        }

        return Response::allow();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['coordinator', 'admin']);
    }

    public function update(User $user, Worksheet $worksheet): Response
    {
        if (! $user->hasAnyRole(['coordinator', 'admin'])) {
            return Response::deny('No tienes permiso para actualizar fichas de trabajo.');
        }

        if ($user->company_id !== $this->resolveCompanyId($worksheet)) {
            return Response::deny('No puedes modificar fichas de trabajo de otra empresa.');
        }

        return Response::allow();
    }

    public function delete(User $user, Worksheet $worksheet): Response
    {
        if (! $user->hasRole('admin')) {
            return Response::deny('Solo los administradores pueden eliminar fichas de trabajo.');
        }

        if ($user->company_id !== $this->resolveCompanyId($worksheet)) {
            return Response::deny('No puedes eliminar fichas de trabajo de otra empresa.');
        }

        return Response::allow();
    }

    private function resolveCompanyId(Worksheet $worksheet): int
    {
        return $worksheet->obligation->company_id;
    }
}
