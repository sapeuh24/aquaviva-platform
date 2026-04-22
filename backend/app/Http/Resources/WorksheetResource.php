<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorksheetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'monitoring_tool'  => $this->monitoring_tool,
            'monitoring_phase' => $this->monitoring_phase,
            'name'             => $this->name,
            'objective'        => $this->objective,
            'target'           => $this->target,
            'observations'     => $this->observations,
            'obligation'       => $this->whenLoaded('obligation', fn () => [
                'id'   => $this->obligation->id,
                'name' => $this->obligation->name,
            ]),
            'indicators_count' => $this->whenCounted('indicators', fn () => $this->indicators_count),
            'created_at'       => $this->created_at?->toISOString(),
        ];
    }
}
