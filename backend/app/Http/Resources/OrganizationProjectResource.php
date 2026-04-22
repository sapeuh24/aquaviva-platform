<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'code'        => $this->code,
            'description' => $this->description,
            'location'    => $this->location,
            'start_date'  => $this->start_date?->toDateString(),
            'end_date'    => $this->end_date?->toDateString(),
            'status'      => $this->status,
            'municipality' => $this->whenLoaded('municipality', fn () => [
                'id'         => $this->municipality->id,
                'name'       => $this->municipality->name,
                'department' => $this->municipality->relationLoaded('department') ? [
                    'id'   => $this->municipality->department->id,
                    'name' => $this->municipality->department->name,
                ] : null,
            ]),
            'obligations_count' => $this->whenCounted('obligations', fn () => $this->obligations_count),
            'created_at'        => $this->created_at?->toISOString(),
        ];
    }
}
