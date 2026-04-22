<?php

namespace App\Services;

use App\Models\Evidence;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EvidenceUploadService
{
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/webp',
        'video/mp4',
        'video/quicktime',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    /**
     * Almacena el archivo en disco y retorna metadata para crear el registro Evidence.
     */
    public function store(UploadedFile $file, int $activityId): array
    {
        $realMimeType = $file->getMimeType();

        if (! in_array($realMimeType, self::ALLOWED_MIME_TYPES, strict: true)) {
            throw new \InvalidArgumentException("Tipo de archivo no permitido: {$realMimeType}");
        }

        $extension    = $file->getClientOriginalExtension();
        $randomName   = Str::random(40) . ($extension ? ".{$extension}" : '');
        $directory    = "evidences/{$activityId}";
        $storagePath  = $file->storeAs($directory, $randomName, 'local');

        return [
            'original_name' => $file->getClientOriginalName(),
            'storage_path'  => $storagePath,
            'mime_type'     => $realMimeType,
            'size_bytes'    => $file->getSize(),
        ];
    }

    public function delete(Evidence $evidence): void
    {
        if (Storage::disk('local')->exists($evidence->storage_path)) {
            Storage::disk('local')->delete($evidence->storage_path);
        }
    }

    /**
     * Genera una URL temporal firmada para descarga segura del archivo.
     */
    public function getDownloadUrl(Evidence $evidence): string
    {
        return url("/api/v1/evidences/{$evidence->id}/download");
    }
}
