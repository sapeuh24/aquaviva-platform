<?php

namespace App\Actions\Auth;

use App\DataTransferObjects\Auth\LoginData;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginAction
{
    /**
     * Autentica al usuario y genera un token de acceso Sanctum con expiracion de 7 dias.
     *
     * @return array{token: string, user: User}
     * @throws ValidationException si las credenciales son incorrectas
     */
    public function execute(LoginData $data): array
    {
        if (! Auth::attempt(['email' => $data->email, 'password' => $data->password])) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken(
            name: 'api-token',
            expiresAt: now()->addDays(7),
        )->plainTextToken;

        return [
            'token' => $token,
            'user'  => $user,
        ];
    }
}
