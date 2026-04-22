<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\MeAction;
use App\DataTransferObjects\Auth\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $result = $action->execute(LoginData::fromRequest($request->validated()));

        return response()->json([
            'token' => $result['token'],
            'user'  => new UserResource($result['user']),
        ]);
    }

    public function logout(Request $request, LogoutAction $action): JsonResponse
    {
        $action->execute($request->user());

        return response()->json(null, 204);
    }

    public function me(Request $request, MeAction $action): UserResource
    {
        return new UserResource($action->execute($request->user()));
    }
}
