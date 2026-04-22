<?php

namespace App\Actions\Companies;

use App\DataTransferObjects\Companies\CompanyData;
use App\Mail\WelcomeCompanyMail;
use App\Models\Company;
use App\Models\User;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateCompanyAction
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
    ) {}

    /**
     * Crea una empresa con su usuario administrador y envía el email de bienvenida.
     *
     * @return array{company: Company, admin: User}
     */
    public function execute(CompanyData $data): array
    {
        // 1. Crear la empresa
        $company = $this->repository->create($data);

        // 2. Generar contraseña temporal (12 chars: 3 upper + 5 lower + 2 digits + 1 symbol + 1 extra)
        $temporaryPassword = strtoupper(Str::random(3))
            . Str::random(5)
            . rand(10, 99)
            . ['!', '@', '#'][rand(0, 2)];

        // 3. Crear el usuario administrador
        $adminUser = User::create([
            'company_id' => $company->id,
            'first_name' => $data->adminFirstName,
            'last_name'  => $data->adminLastName,
            'email'      => $data->adminEmail,
            'phone'      => $data->adminPhone,
            'password'   => $temporaryPassword,
            'is_active'  => true,
        ]);

        // 4. Asignar rol de administrador de empresa
        $adminUser->assignRole('admin');

        // 5. Enviar email de bienvenida con credenciales
        Mail::to($adminUser->email)->send(
            new WelcomeCompanyMail($company, $adminUser, $temporaryPassword)
        );

        return [
            'company' => $company,
            'admin'   => $adminUser,
        ];
    }
}
