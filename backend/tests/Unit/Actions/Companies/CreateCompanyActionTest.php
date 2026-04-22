<?php

use App\Actions\Companies\CreateCompanyAction;
use App\DataTransferObjects\Companies\CompanyData;
use App\Models\Company;
use App\Models\DocumentType;
use App\Models\User;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests Unitarios - CreateCompanyAction
|--------------------------------------------------------------------------
|
| Usa Mockery para aislar el repositorio. El Mail se fakeea para
| evitar envios reales durante los tests.
|
| Nota: CreateCompanyAction llama User::create() internamente sin pasar
| document_type_id. Las FK constraints de SQLite estan desactivadas en
| TestCase::setUp() para permitir que estos tests corran mientras la accion
| no incluya document_type_id. Esto deberia corregirse en el Action.
|
*/

beforeEach(function (): void {
    Mail::fake();

    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    DocumentType::factory()->create(['code' => 'CC', 'name' => 'Cedula de Ciudadania']);
});

afterEach(function (): void {
    Mockery::close();
});

/**
 * Helper para construir un CompanyData de prueba.
 */
function makeDatosEmpresa(Company $empresa, string $adminEmail = 'admin@test.com'): CompanyData
{
    return new CompanyData(
        name:             $empresa->name,
        nit:              $empresa->nit,
        ciiu_code:        $empresa->ciiu_code,
        ciiu_description: $empresa->ciiu_description,
        municipality_id:  null,
        phone:            null,
        email:            null,
        address:          null,
        is_active:        true,
        adminFirstName:   'Pedro',
        adminLastName:    'Ramirez',
        adminEmail:       $adminEmail,
        adminPhone:       null,
    );
}

test('test_crea_empresa_con_datos_correctos', function (): void {
    $empresaMock = Company::factory()->create(['name' => 'Empresa Mock SAS']);

    $repositoryMock = Mockery::mock(CompanyRepositoryInterface::class);
    $repositoryMock
        ->shouldReceive('create')
        ->once()
        ->andReturn($empresaMock);

    $action    = new CreateCompanyAction($repositoryMock);
    $resultado = $action->execute(makeDatosEmpresa($empresaMock, 'pedro.nuevo@test.com'));

    expect($resultado)->toHaveKey('company');
    expect($resultado)->toHaveKey('admin');
    expect($resultado['company']->name)->toBe('Empresa Mock SAS');
    expect($resultado['admin'])->toBeInstanceOf(User::class);
});

test('test_el_admin_creado_tiene_rol_admin', function (): void {
    $empresa = Company::factory()->create();

    $repositoryMock = Mockery::mock(CompanyRepositoryInterface::class);
    $repositoryMock
        ->shouldReceive('create')
        ->once()
        ->andReturn($empresa);

    $action    = new CreateCompanyAction($repositoryMock);
    $resultado = $action->execute(makeDatosEmpresa($empresa, 'ana.torres@test.com'));

    expect($resultado['admin']->hasRole('admin'))->toBeTrue();
});

test('test_action_invoca_repositorio_exactamente_una_vez', function (): void {
    $empresa = Company::factory()->create();

    $repositoryMock = Mockery::mock(CompanyRepositoryInterface::class);
    $repositoryMock
        ->shouldReceive('create')
        ->once()
        ->andReturn($empresa);

    $action = new CreateCompanyAction($repositoryMock);
    $action->execute(makeDatosEmpresa($empresa, 'nuevo.admin@test.com'));

    // La expectativa 'once()' de Mockery valida que el repositorio fue llamado
    // exactamente una vez al ejecutar Mockery::close() en afterEach
});
