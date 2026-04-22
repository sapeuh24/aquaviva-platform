<?php

namespace App\Http\Controllers\Api;

use App\Actions\Indicators\CreateIndicatorAction;
use App\Actions\Indicators\DeleteIndicatorAction;
use App\Actions\Indicators\ListIndicatorsAction;
use App\Actions\Indicators\UpdateIndicatorAction;
use App\DataTransferObjects\Indicators\IndicatorData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Indicators\StoreIndicatorRequest;
use App\Http\Requests\Indicators\UpdateIndicatorRequest;
use App\Http\Resources\IndicatorResource;
use App\Models\Indicator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndicatorController extends Controller
{
    public function index(Request $request, ListIndicatorsAction $action): AnonymousResourceCollection
    {
        $indicators = $action->execute(
            filters: $request->only(['worksheet_id', 'is_active', 'overdue']),
            perPage: (int) $request->get('per_page', 15),
        );

        return IndicatorResource::collection($indicators);
    }

    public function store(StoreIndicatorRequest $request, CreateIndicatorAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['company_id' => $request->user()->company_id]);
        $indicator = $action->execute(IndicatorData::fromRequest($data));

        return (new IndicatorResource($indicator->load('indicatorFrequency')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Indicator $indicator): IndicatorResource
    {
        return new IndicatorResource($indicator->load('indicatorFrequency')->loadCount('activities'));
    }

    public function update(UpdateIndicatorRequest $request, Indicator $indicator, UpdateIndicatorAction $action): IndicatorResource
    {
        $updated = $action->execute($indicator, IndicatorData::fromRequest($request->validated()));

        return new IndicatorResource($updated->loadCount('activities'));
    }

    public function destroy(Indicator $indicator, DeleteIndicatorAction $action): JsonResponse
    {
        $action->execute($indicator);

        return response()->json(null, 204);
    }
}
