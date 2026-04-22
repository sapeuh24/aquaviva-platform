<?php

use App\Models\Company;
use App\Models\DocumentType;
use App\Models\Obligation;
use App\Models\OrganizationProject;
use App\Models\User;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests de Obligaciones Ambientales (CRUD /api/v1/obligations)
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

test('test_crear_obligacion', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $datos = [
        'company_id'           => $this->empresa->id,
        'name'                 => 'Tratamiento de aguas residuales',
        'description'          => 'Implementar PTAR antes del mes 6',
        'resolution_number'    => 'RES-2024-0001',
        'resolution_date'      => '2024-01-15',
        'instrument_type'      => 'Licencia Ambiental',
        'environmental_medium' => 'Agua',
        'obligation_type'      => 'Medida de manejo',
        'compliance_deadline'  => '2024-12-31',
        'compliance_frequency' => 'Trimestral',
        'status'               => 'vigente',
    ];

    $respuesta = $this->postJson('/api/v1/obligations', $datos);

    $respuesta->assertStatus(201)
        ->assertJsonFragment(['name' => 'Tratamiento de aguas residuales']);

    $this->assertDatabaseHas('obligations', [
        'company_id'        => $this->empresa->id,
        'resolution_number' => 'RES-2024-0001',
    ]);
});

test('test_listar_obligaciones_por_proyecto', function (): void {
    $proyecto   = OrganizationProject::factory()->create(['company_id' => $this->empresa->id]);
    $obligacion = Obligation::factory()->create(['company_id' => $this->empresa->id]);
    $proyecto->obligations()->attach($obligacion->id);

    $this->actingAs($this->coordinator, 'sanctum');

    $respuesta = $this->getJson("/api/v1/obligations/by-project/{$proyecto->id}");

    $respuesta->assertStatus(200);
    expect(count($respuesta->json('data')))->toBeGreaterThanOrEqual(1);
});

test('test_listar_obligaciones_solo_empresa_propia', function (): void {
    // Obligacion propia
    Obligation::factory()->create(['company_id' => $this->empresa->id]);

    // Obligacion de otra empresa — no debe aparecer por el GlobalScope
    $otraEmpresa = Company::factory()->create();
    Obligation::factory()->create(['company_id' => $otraEmpresa->id]);

    $this->actingAs($this->coordinator, 'sanctum');

    $respuesta = $this->getJson('/api/v1/obligations');

    $respuesta->assertStatus(200);
    expect(count($respuesta->json('data')))->toBe(1);
});

test('test_listar_obligaciones_requiere_autenticacion', function (): void {
    $this->getJson('/api/v1/obligations')
        ->assertStatus(401);
});

test('test_crear_obligacion_sin_nombre_falla', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $datos = [
        'company_id' => $this->empresa->id,
        'status'     => 'vigente',
        // falta 'name'
    ];

    $this->postJson('/api/v1/obligations', $datos)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});
