<?php

namespace App\Http\Controllers\Api;

use App\Actions\Evidences\DeleteEvidenceAction;
use App\Actions\Evidences\DownloadEvidenceAction;
use App\Actions\Evidences\ListEvidencesAction;
use App\Actions\Evidences\UploadEvidenceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evidences\StoreEvidenceRequest;
use App\Http\Resources\EvidenceResource;
use App\Models\Evidence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EvidenceController extends Controller
{
    public function index(Request $request, ListEvidencesAction $action): AnonymousResourceCollection
    {
        $evidences = $action->execute(
            filters: $request->only(['activity_id', 'mime_type']),
            perPage: (int) $request->get('per_page', 15),
        );

        return EvidenceResource::collection($evidences);
    }

    public function store(StoreEvidenceRequest $request, UploadEvidenceAction $action): JsonResponse
    {
        $evidence = $action->execute($request);

        return (new EvidenceResource($evidence->load(['activity', 'uploader'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Evidence $evidence): EvidenceResource
    {
        return new EvidenceResource($evidence->load(['uploader', 'activity']));
    }

    public function destroy(Evidence $evidence, DeleteEvidenceAction $action, Request $request): JsonResponse
    {
        $action->execute($evidence, $request->user());

        return response()->json(null, 204);
    }

    public function download(Evidence $evidence, DownloadEvidenceAction $action): StreamedResponse
    {
        return $action->execute($evidence);
    }
}
