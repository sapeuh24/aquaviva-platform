<?php

namespace App\Http\Controllers\Api;

use App\Actions\Users\CreateUserAction;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\ListUsersAction;
use App\Actions\Users\UpdateUserAction;
use App\DataTransferObjects\Users\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(Request $request, ListUsersAction $action): AnonymousResourceCollection
    {
        $users = $action->execute(
            filters: $request->only(['search', 'company_id', 'is_active']),
            perPage: (int) $request->get('per_page', 15),
        );

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request, CreateUserAction $action): JsonResponse
    {
        $user = $action->execute(UserData::fromRequest($request->validated()));

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user->load(['company.municipality.department', 'documentType', 'roles']));
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): UserResource
    {
        $updated = $action->execute($user, UserData::fromRequest($request->validated()));

        return new UserResource($updated);
    }

    public function destroy(User $user, DeleteUserAction $action): JsonResponse
    {
        $action->execute($user);

        return response()->json(null, 204);
    }
}
