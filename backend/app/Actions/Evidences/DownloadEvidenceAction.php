<?php

namespace App\Actions\Evidences;

use App\Models\Evidence;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadEvidenceAction
{
    public function execute(Evidence $evidence): StreamedResponse
    {
        $path = $evidence->storage_path;

        if (! Storage::disk('local')->exists($path)) {
            abort(404, 'El archivo no existe en el servidor.');
        }

        return response()->streamDownload(
            function () use ($path): void {
                echo Storage::disk('local')->get($path);
            },
            $evidence->original_name,
            ['Content-Type' => $evidence->mime_type],
        );
    }
}
