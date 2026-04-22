<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvidenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'original_name' => $this->original_name,
            'mime_type'     => $this->mime_type,
            'size_bytes'    => $this->size_bytes,
            'size_human'    => $this->formatBytes($this->size_bytes),
            'description'   => $this->description,
            'activity'      => $this->whenLoaded('activity', fn () => [
                'id'   => $this->activity->id,
                'name' => $this->activity->name,
            ]),
            'uploaded_by' => $this->whenLoaded('uploader', fn () => [
                'id'        => $this->uploader->id,
                'full_name' => $this->uploader->full_name,
            ]),
            'created_at'   => $this->created_at?->toISOString(),
            'download_url' => url("/api/v1/evidences/{$this->id}/download"),
        ];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        return number_format($bytes / 1024, 2) . ' KB';
    }
}
