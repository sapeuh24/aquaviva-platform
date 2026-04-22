<?php

namespace App\Http\Controllers\Api;

use App\Actions\Obligations\CreateObligationAction;
use App\Actions\Obligations\DeleteObligationAction;
use App\Actions\Obligations\ListByOrganizationProjectAction;
use App\Actions\Obligations\ListObligationsAction;
use App\Actions\Obligations\UpdateObligationAction;
use App\DataTransferObjects\Obligations\ObligationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Obligations\StoreObligationRequest;
use App\Http\Requests\Obligations\UpdateObligationRequest;
use App\Http\Resources\ObligationResource;
use App\Models\Obligation;
use App\Models\OrganizationProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ObligationController extends Controller
{
    public function index(Request $request, ListObligationsAction $action): AnonymousResourceCollection
    {
        $obligations = $action->execute(
            filters: $request->only(['search', 'status', 'environmental_authority_id', 'deadline_from', 'deadline_to']),
            perPage: (int) $request->get('per_page', 15),
        );

        return ObligationResource::collection($obligations);
    }

    public function store(StoreObligationRequest $request, CreateObligationAction $action): JsonResponse
    {
        $obligation = $action->execute(ObligationData::fromRequest($request->validated()));

        return (new ObligationResource($obligation->load(['environmentalAuthority', 'organizationProjects'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Obligation $obligation): ObligationResource
    {
        return new ObligationResource(
            $obligation->load(['environmentalAuthority', 'monitoring', 'organizationProjects'])->loadCount('worksheets')
        );
    }

    public function update(UpdateObligationRequest $request, Obligation $obligation, UpdateObligationAction $action): ObligationResource
    {
        $updated = $action->execute($obligation, ObligationData::fromRequest($request->validated()));

        return new ObligationResource($updated->loadCount('worksheets'));
    }

    public function destroy(Obligation $obligation, DeleteObligationAction $action): JsonResponse
    {
        $action->execute($obligation);

        return response()->json(null, 204);
    }

    public function byOrganizationProject(
        OrganizationProject $organizationProject,
        ListByOrganizationProjectAction $action,
    ): AnonymousResourceCollection {
        return ObligationResource::collection($action->execute($organizationProject));
    }
}
