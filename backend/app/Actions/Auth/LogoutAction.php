<?php

namespace App\Actions\Auth;

use App\Models\User;

class LogoutAction
{
    public function execute(User $user): void
    {
        $token = $user->currentAccessToken();

        if ($token && method_exists($token, 'delete')) {
            $token->delete();
        }
    }
}
