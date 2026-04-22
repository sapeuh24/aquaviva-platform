<?php

namespace App\Http\Controllers\Api;

use App\Actions\Activities\AssignUserAction;
use App\Actions\Activities\CreateActivityAction;
use App\Actions\Activities\DeleteActivityAction;
use App\Actions\Activities\ListActivitiesAction;
use App\Actions\Activities\UnassignUserAction;
use App\Actions\Activities\UpdateActivityAction;
use App\DataTransferObjects\Activities\ActivityData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activities\StoreActivityRequest;
use App\Http\Requests\Activities\UpdateActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityController extends Controller
{
    public function index(Request $request, ListActivitiesAction $action): AnonymousResourceCollection
    {
        $activities = $action->execute(
            filters: $request->only(['indicator_id', 'compliance_status', 'scheduled_from', 'scheduled_to']),
            perPage: (int) $request->get('per_page', 15),
        );

        return ActivityResource::collection($activities);
    }

    public function store(StoreActivityRequest $request, CreateActivityAction $action): JsonResponse
    {
        $data = array_merge($request->validated(), ['company_id' => $request->user()->company_id]);
        $activity = $action->execute(ActivityData::fromRequest($data));

        return (new ActivityResource($activity->load(['indicator', 'users'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Activity $activity): ActivityResource
    {
        return new ActivityResource($activity->load(['indicator', 'users'])->loadCount('evidences'));
    }

    public function update(UpdateActivityRequest $request, Activity $activity, UpdateActivityAction $action): ActivityResource
    {
        $data = array_merge($request->validated(), ['company_id' => $request->user()->company_id]);
        $updated = $action->execute($activity, ActivityData::fromRequest($data));

        return new ActivityResource($updated->loadCount('evidences'));
    }

    public function destroy(Activity $activity, DeleteActivityAction $action): JsonResponse
    {
        $action->execute($activity);

        return response()->json(null, 204);
    }

    public function assignUser(Activity $activity, User $user, AssignUserAction $action): JsonResponse
    {
        $action->execute($activity, $user);

        return response()->json(['message' => 'Usuario asignado correctamente.']);
    }

    public function unassignUser(Activity $activity, User $user, UnassignUserAction $action): JsonResponse
    {
        $action->execute($activity, $user);

        return response()->json(null, 204);
    }
}
