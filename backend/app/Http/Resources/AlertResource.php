<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlertResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'title'      => $this->title,
            'message'    => $this->message,
            'due_date'   => $this->due_date?->toDateString(),
            'status'     => $this->status,
            'is_read'    => ! is_null($this->read_at),
            'read_at'    => $this->read_at?->toISOString(),
            'indicator'  => $this->whenLoaded('indicator', fn () => [
                'id'   => $this->indicator->id,
                'name' => $this->indicator->name,
            ]),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
