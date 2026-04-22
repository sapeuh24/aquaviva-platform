<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'full_name'  => $this->full_name,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'is_active'  => $this->is_active,
            'company'    => $this->whenLoaded('company', fn () => new CompanyResource($this->company)),
            'roles'      => $this->whenLoaded('roles', fn () => $this->roles->pluck('name')->toArray()),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
