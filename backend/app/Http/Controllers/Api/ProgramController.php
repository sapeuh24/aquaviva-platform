<?php

namespace App\Http\Controllers\Api;

use App\Actions\Programs\CreateProgramAction;
use App\Actions\Programs\DeleteProgramAction;
use App\Actions\Programs\ListProgramsAction;
use App\Actions\Programs\UpdateProgramAction;
use App\DataTransferObjects\Programs\ProgramData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Programs\StoreProgramRequest;
use App\Http\Requests\Programs\UpdateProgramRequest;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\ProjectResource;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProgramController extends Controller
{
    public function index(Request $request, ListProgramsAction $action): AnonymousResourceCollection
    {
        $programs = $action->execute(
            filters: $request->only(['search', 'company_id', 'environmental_medium_id', 'is_active']),
            perPage: (int) $request->get('per_page', 15),
        );

        return ProgramResource::collection($programs);
    }

    public function store(StoreProgramRequest $request, CreateProgramAction $action): JsonResponse
    {
        $program = $action->execute(ProgramData::fromRequest($request->validated()));

        return (new ProgramResource($program->load('environmentalMedium')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Program $program): ProgramResource
    {
        return new ProgramResource($program->load('environmentalMedium')->loadCount('projects'));
    }

    public function update(UpdateProgramRequest $request, Program $program, UpdateProgramAction $action): ProgramResource
    {
        $updated = $action->execute($program, ProgramData::fromRequest($request->validated()));

        return new ProgramResource($updated->loadCount('projects'));
    }

    public function destroy(Program $program, DeleteProgramAction $action): JsonResponse
    {
        $action->execute($program);

        return response()->json(null, 204);
    }

    public function projects(Program $program, Request $request): AnonymousResourceCollection
    {
        $projects = $program->projects()
            ->with(['monitoring'])
            ->paginate((int) $request->get('per_page', 15));

        return ProjectResource::collection($projects);
    }
}
