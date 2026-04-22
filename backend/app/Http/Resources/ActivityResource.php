<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'indicator_id'      => $this->indicator_id,
            'name'              => $this->name,
            'description'       => $this->description,
            'scheduled_date'    => $this->scheduled_date?->toDateString(),
            'executed_date'     => $this->executed_date?->toDateString(),
            'compliance_status' => $this->compliance_status,
            'notes'             => $this->notes,
            'indicator'         => $this->whenLoaded('indicator', fn () => [
                'id'   => $this->indicator->id,
                'name' => $this->indicator->name,
            ]),
            'evidences_count' => $this->whenCounted('evidences', fn () => $this->evidences_count),
            'assigned_users'  => $this->whenLoaded('users', fn () => $this->users->map(fn ($u) => [
                'id'        => $u->id,
                'full_name' => $u->full_name,
            ])->values()->all()),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
