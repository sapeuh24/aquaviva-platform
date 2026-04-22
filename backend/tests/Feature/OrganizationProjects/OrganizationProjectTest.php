<?php

use App\Models\Company;
use App\Models\DocumentType;
use App\Models\Obligation;
use App\Models\OrganizationProject;
use App\Models\User;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests de Proyectos de Organizacion (CRUD /api/v1/organization-projects)
|--------------------------------------------------------------------------
*/

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'coordinator', 'guard_name' => 'web']);

    $this->tipoDoc = DocumentType::factory()->create();
    $this->empresa = Company::factory()->create();

    $this->coordinator = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $this->coordinator->assignRole('coordinator');
});

test('test_crear_proyecto_organizacion', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $datos = [
        'company_id'  => $this->empresa->id,
        'name'        => 'Construccion Planta Norte',
        'code'        => 'CPN-2024',
        'description' => 'Expansion de la planta de produccion norte',
        'location'    => 'Zona Industrial Norte, Bogota',
        'start_date'  => '2024-01-01',
        'end_date'    => '2025-12-31',
        'status'      => 'activo',
    ];

    $respuesta = $this->postJson('/api/v1/organization-projects', $datos);

    $respuesta->assertStatus(201)
        ->assertJsonFragment(['name' => 'Construccion Planta Norte']);

    $this->assertDatabaseHas('organization_projects', [
        'company_id' => $this->empresa->id,
        'code'       => 'CPN-2024',
    ]);
});

test('test_listar_proyectos_requiere_autenticacion', function (): void {
    $this->getJson('/api/v1/organization-projects')
        ->assertStatus(401);
});

test('test_listar_proyectos_retorna_solo_empresa_propia', function (): void {
    // Proyecto de la empresa propia
    OrganizationProject::factory()->create([
        'company_id' => $this->empresa->id,
    ]);

    // Proyecto de otra empresa — no debe aparecer
    $otraEmpresa = Company::factory()->create();
    OrganizationProject::factory()->create([
        'company_id' => $otraEmpresa->id,
    ]);

    $this->actingAs($this->coordinator, 'sanctum');

    $respuesta = $this->getJson('/api/v1/organization-projects');

    $respuesta->assertStatus(200);
    expect(count($respuesta->json('data')))->toBe(1);
});

test('test_vincular_obligacion_a_proyecto', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $proyecto = OrganizationProject::factory()->create([
        'company_id' => $this->empresa->id,
    ]);

    $obligacion = Obligation::factory()->create([
        'company_id' => $this->empresa->id,
    ]);

    $respuesta = $this->postJson(
        "/api/v1/organization-projects/{$proyecto->id}/obligations/{$obligacion->id}"
    );

    $respuesta->assertStatus(200)
        ->assertJsonFragment(['message' => 'Obligacion vinculada correctamente.']);

    $this->assertDatabaseHas('obligation_organization_project', [
        'organization_project_id' => $proyecto->id,
        'obligation_id'           => $obligacion->id,
    ]);
});

test('test_desvincular_obligacion_de_proyecto', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $proyecto   = OrganizationProject::factory()->create(['company_id' => $this->empresa->id]);
    $obligacion = Obligation::factory()->create(['company_id' => $this->empresa->id]);

    // Vincular primero
    $proyecto->obligations()->attach($obligacion->id);

    $respuesta = $this->deleteJson(
        "/api/v1/organization-projects/{$proyecto->id}/obligations/{$obligacion->id}"
    );

    $respuesta->assertStatus(204);

    $this->assertDatabaseMissing('obligation_organization_project', [
        'organization_project_id' => $proyecto->id,
        'obligation_id'           => $obligacion->id,
    ]);
});
