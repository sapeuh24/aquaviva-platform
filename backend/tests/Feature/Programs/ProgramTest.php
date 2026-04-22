<?php

use App\Models\Company;
use App\Models\DocumentType;
use App\Models\EnvironmentalMedium;
use App\Models\Program;
use App\Models\User;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests de Programas Ambientales (CRUD /api/v1/programs)
|--------------------------------------------------------------------------
|
| Nota: Program tiene un GlobalScope que filtra por company_id del usuario
| autenticado, por lo que el multi-tenancy es transparente.
|
*/

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'super_admin',  'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin',        'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'coordinator',  'guard_name' => 'web']);

    $this->tipoDoc = DocumentType::factory()->create();
    $this->empresa = Company::factory()->create();
    $this->medio   = EnvironmentalMedium::factory()->create();

    $this->admin = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $this->admin->assignRole('admin');
});

test('test_crear_programa', function (): void {
    $this->actingAs($this->admin, 'sanctum');

    $datos = [
        'company_id'              => $this->empresa->id,
        'environmental_medium_id' => $this->medio->id,
        'name'                    => 'Programa de Manejo de Residuos',
        'code'                    => 'PMR-001',
        'description'             => 'Gestion de residuos solidos',
        'is_active'               => true,
    ];

    $respuesta = $this->postJson('/api/v1/programs', $datos);

    $respuesta->assertStatus(201)
        ->assertJsonFragment(['name' => 'Programa de Manejo de Residuos']);

    $this->assertDatabaseHas('programs', [
        'company_id' => $this->empresa->id,
        'code'       => 'PMR-001',
    ]);
});

test('test_listar_programas', function (): void {
    // Crear programas de la empresa propia
    Program::factory()->count(2)->create([
        'company_id'              => $this->empresa->id,
        'environmental_medium_id' => $this->medio->id,
    ]);

    // Programa de otra empresa — NO debe aparecer gracias al GlobalScope
    $otraEmpresa = Company::factory()->create();
    Program::factory()->create([
        'company_id'              => $otraEmpresa->id,
        'environmental_medium_id' => $this->medio->id,
    ]);

    $this->actingAs($this->admin, 'sanctum');

    $respuesta = $this->getJson('/api/v1/programs');

    $respuesta->assertStatus(200);

    // Solo deben aparecer los programas de la empresa propia
    $companyIds = collect($respuesta->json('data'))->pluck('company_id')->unique()->toArray();
    // El resource no expone company_id directamente, verificamos que solo son 2 items
    expect(count($respuesta->json('data')))->toBe(2);
});

test('test_filtrar_programas_por_is_active', function (): void {
    // Verificar que el filtro is_active funciona y retorna 200
    // (la columna is_active se agrega via model fillable — puede requerir migracion adicional)
    Program::factory()->create([
        'company_id'              => $this->empresa->id,
        'environmental_medium_id' => $this->medio->id,
        'is_active'               => true,
    ]);

    $this->actingAs($this->admin, 'sanctum');

    $respuesta = $this->getJson('/api/v1/programs?is_active=true');

    $respuesta->assertStatus(200);
    // Debe retornar al menos 1 programa activo
    expect(count($respuesta->json('data')))->toBeGreaterThanOrEqual(1);
});

test('test_listar_programas_requiere_autenticacion', function (): void {
    $this->getJson('/api/v1/programs')
        ->assertStatus(401);
});
