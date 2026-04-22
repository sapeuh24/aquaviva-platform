<?php

namespace App\DataTransferObjects\Companies;

readonly class CompanyData
{
    public function __construct(
        public string  $name,
        public string  $nit,
        public string  $ciiu_code,
        public string  $ciiu_description,
        public ?int    $municipality_id,
        public ?string $phone,
        public ?string $email,
        public ?string $address,
        public bool    $is_active = true,
        // Datos del usuario administrador (solo en creación)
        public ?string $adminFirstName = null,
        public ?string $adminLastName  = null,
        public ?string $adminEmail     = null,
        public ?string $adminPhone     = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            name:             $validated['name'],
            nit:              $validated['nit'],
            ciiu_code:        $validated['ciiu_code'],
            ciiu_description: $validated['ciiu_description'],
            municipality_id:  $validated['municipality_id'] ?? null,
            phone:            $validated['phone'] ?? null,
            email:            $validated['email'] ?? null,
            address:          $validated['address'] ?? null,
            is_active:        $validated['is_active'] ?? true,
            adminFirstName:   $validated['admin_first_name'] ?? null,
            adminLastName:    $validated['admin_last_name'] ?? null,
            adminEmail:       $validated['admin_email'] ?? null,
            adminPhone:       $validated['admin_phone'] ?? null,
        );
    }
}
