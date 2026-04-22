<?php

namespace App\Policies;

use App\Models\Evidence;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EvidencePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer']);
    }

    public function view(User $user, Evidence $evidence): Response
    {
        if (! $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer'])) {
            return Response::deny('No tienes permiso para ver evidencias.');
        }

        if ($user->company_id !== $this->resolveCompanyId($evidence)) {
            return Response::deny('No puedes acceder a evidencias de otra empresa.');
        }

        return Response::allow();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['analyst', 'coordinator', 'admin']);
    }

    public function delete(User $user, Evidence $evidence): Response
    {
        if ($user->id !== $evidence->uploaded_by) {
            return Response::deny('Solo el usuario que subio la evidencia puede eliminarla.');
        }

        return Response::allow();
    }

    public function download(User $user, Evidence $evidence): Response
    {
        if (! $user->hasAnyRole(['analyst', 'coordinator', 'admin', 'viewer'])) {
            return Response::deny('No tienes permiso para descargar evidencias.');
        }

        if ($user->company_id !== $this->resolveCompanyId($evidence)) {
            return Response::deny('No puedes descargar evidencias de otra empresa.');
        }

        return Response::allow();
    }

    /**
     * Navega la cadena de relaciones para obtener el company_id cuando
     * no se puede confiar únicamente en evidence.company_id.
     */
    private function resolveCompanyId(Evidence $evidence): int
    {
        return $evidence->activity->indicator->worksheet->obligation->company_id;
    }
}
