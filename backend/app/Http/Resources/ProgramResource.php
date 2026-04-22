<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'code'        => $this->code,
            'description' => $this->description,
            'is_active'   => $this->is_active,
            'environmental_medium' => $this->whenLoaded('environmentalMedium', fn () => [
                'id'   => $this->environmentalMedium->id,
                'name' => $this->environmentalMedium->name,
            ]),
            'projects_count' => $this->whenCounted('projects', fn () => $this->projects_count),
            'created_at'     => $this->created_at?->toISOString(),
        ];
    }
}
