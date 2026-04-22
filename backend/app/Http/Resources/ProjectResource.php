<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'code'        => $this->code,
            'description' => $this->description,
            'start_date'  => $this->start_date?->toDateString(),
            'end_date'    => $this->end_date?->toDateString(),
            'status'      => $this->status,
            'program'     => $this->whenLoaded('program', fn () => [
                'id'   => $this->program->id,
                'name' => $this->program->name,
            ]),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
