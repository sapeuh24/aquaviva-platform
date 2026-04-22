<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * El usuario admin recién creado se inyecta manualmente solo en la respuesta
     * de creación (StoreCompanyController), no proviene de la BD directamente.
     *
     * Uso: (new CompanyResource($company))->withAdmin($adminUser)
     */
    public mixed $adminUser = null;

    public function withAdmin(mixed $user): static
    {
        $this->adminUser = $user;

        return $this;
    }

    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'nit'              => $this->nit,
            'ciiu_code'        => $this->ciiu_code,
            'ciiu_description' => $this->ciiu_description,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'address'          => $this->address,
            'is_active'        => $this->is_active,
            'municipality'     => $this->whenLoaded('municipality', fn () => [
                'id'         => $this->municipality->id,
                'name'       => $this->municipality->name,
                'dane_code'  => $this->municipality->dane_code,
                'department' => $this->municipality->relationLoaded('department') ? [
                    'id'        => $this->municipality->department->id,
                    'name'      => $this->municipality->department->name,
                    'dane_code' => $this->municipality->department->dane_code,
                ] : null,
            ]),
            'created_at' => $this->created_at?->toISOString(),

            // Solo presente cuando la empresa acaba de ser creada con su admin
            'admin_user' => $this->adminUser !== null
                ? new UserResource($this->adminUser)
                : null,
        ];
    }
}
