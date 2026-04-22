<?php

namespace App\Http\Controllers\Api;

use App\Actions\OrganizationProjects\AttachObligationAction;
use App\Actions\OrganizationProjects\CreateOrganizationProjectAction;
use App\Actions\OrganizationProjects\DeleteOrganizationProjectAction;
use App\Actions\OrganizationProjects\DetachObligationAction;
use App\Actions\OrganizationProjects\ListOrganizationProjectsAction;
use App\Actions\OrganizationProjects\UpdateOrganizationProjectAction;
use App\DataTransferObjects\OrganizationProjects\OrganizationProjectData;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationProjects\StoreOrganizationProjectRequest;
use App\Http\Requests\OrganizationProjects\UpdateOrganizationProjectRequest;
use App\Http\Resources\OrganizationProjectResource;
use App\Models\Obligation;
use App\Models\OrganizationProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrganizationProjectController extends Controller
{
    public function index(Request $request, ListOrganizationProjectsAction $action): AnonymousResourceCollection
    {
        $projects = $action->execute(
            filters: $request->only(['search', 'status', 'municipality_id']),
            perPage: (int) $request->get('per_page', 15),
        );

        return OrganizationProjectResource::collection($projects);
    }

    public function store(StoreOrganizationProjectRequest $request, CreateOrganizationProjectAction $action): JsonResponse
    {
        $project = $action->execute(OrganizationProjectData::fromRequest($request->validated()));

        return (new OrganizationProjectResource($project->load('municipality.department')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(OrganizationProject $organizationProject): OrganizationProjectResource
    {
        return new OrganizationProjectResource(
            $organizationProject->load('municipality.department')->loadCount('obligations')
        );
    }

    public function update(UpdateOrganizationProjectRequest $request, OrganizationProject $organizationProject, UpdateOrganizationProjectAction $action): OrganizationProjectResource
    {
        $updated = $action->execute($organizationProject, OrganizationProjectData::fromRequest($request->validated()));

        return new OrganizationProjectResource($updated->loadCount('obligations'));
    }

    public function destroy(OrganizationProject $organizationProject, DeleteOrganizationProjectAction $action): JsonResponse
    {
        $action->execute($organizationProject);

        return response()->json(null, 204);
    }

    public function attachObligation(OrganizationProject $organizationProject, Obligation $obligation, AttachObligationAction $action): JsonResponse
    {
        $action->execute($organizationProject, $obligation);

        return response()->json(['message' => 'Obligacion vinculada correctamente.']);
    }

    public function detachObligation(OrganizationProject $organizationProject, Obligation $obligation, DetachObligationAction $action): JsonResponse
    {
        $action->execute($organizationProject, $obligation);

        return response()->json(null, 204);
    }
}
