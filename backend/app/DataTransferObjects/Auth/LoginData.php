<?php

namespace App\DataTransferObjects\Auth;

readonly class LoginData
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            email: $validated['email'],
            password: $validated['password'],
        );
    }
}
