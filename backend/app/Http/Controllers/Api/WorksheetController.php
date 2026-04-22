<?php

namespace App\Http\Controllers\Api;

use App\Actions\Worksheets\CreateWorksheetAction;
use App\Actions\Worksheets\DeleteWorksheetAction;
use App\Actions\Worksheets\ListWorksheetsAction;
use App\Actions\Worksheets\UpdateWorksheetAction;
use App\DataTransferObjects\Worksheets\WorksheetData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheets\StoreWorksheetRequest;
use App\Http\Requests\Worksheets\UpdateWorksheetRequest;
use App\Http\Resources\WorksheetResource;
use App\Models\Worksheet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WorksheetController extends Controller
{
    public function index(Request $request, ListWorksheetsAction $action): AnonymousResourceCollection
    {
        $worksheets = $action->execute(
            filters: $request->only(['obligation_id', 'monitoring_id']),
            perPage: (int) $request->get('per_page', 15),
        );

        return WorksheetResource::collection($worksheets);
    }

    public function store(StoreWorksheetRequest $request, CreateWorksheetAction $action): JsonResponse
    {
        $worksheet = $action->execute(WorksheetData::fromRequest($request->validated()));

        return (new WorksheetResource($worksheet->load('obligation')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Worksheet $worksheet): WorksheetResource
    {
        return new WorksheetResource($worksheet->load('obligation')->loadCount('indicators'));
    }

    public function update(UpdateWorksheetRequest $request, Worksheet $worksheet, UpdateWorksheetAction $action): WorksheetResource
    {
        $updated = $action->execute($worksheet, WorksheetData::fromRequest($request->validated()));

        return new WorksheetResource($updated->loadCount('indicators'));
    }

    public function destroy(Worksheet $worksheet, DeleteWorksheetAction $action): JsonResponse
    {
        $action->execute($worksheet);

        return response()->json(null, 204);
    }
}
