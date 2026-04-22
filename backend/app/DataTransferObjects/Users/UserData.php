<?php

namespace App\DataTransferObjects\Users;

readonly class UserData
{
    public function __construct(
        public int $company_id,
        public int $document_type_id,
        public string $document_number,
        public string $first_name,
        public string $last_name,
        public string $email,
        public ?string $phone,
        public ?string $password,
        public bool $is_active,
        public array $roles,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            company_id: $validated['company_id'],
            document_type_id: $validated['document_type_id'],
            document_number: $validated['document_number'],
            first_name: $validated['first_name'],
            last_name: $validated['last_name'],
            email: $validated['email'],
            phone: $validated['phone'] ?? null,
            password: $validated['password'] ?? null,
            is_active: $validated['is_active'] ?? true,
            roles: $validated['roles'] ?? [],
        );
    }
}
