<?php

use App\Models\Company;
use App\Models\DocumentType;
use App\Models\Obligation;
use App\Models\User;
use App\Models\Worksheet;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests de Fichas de Monitoreo (CRUD /api/v1/worksheets)
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

    $this->obligacion = Obligation::factory()->create([
        'company_id' => $this->empresa->id,
    ]);
});

test('test_crear_ficha_monitoreo', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $datos = [
        'company_id'       => $this->empresa->id,
        'obligation_id'    => $this->obligacion->id,
        'name'             => 'Ficha de Monitoreo de Agua',
        'monitoring_tool'  => 'Medidor de caudal',
        'monitoring_phase' => 'Construccion',
        'objective'        => 'Monitorear calidad del agua',
        'target'           => 'Mantener DBO < 30 mg/L',
        'observations'     => 'Medicion mensual en punto de descarga',
    ];

    $respuesta = $this->postJson('/api/v1/worksheets', $datos);

    $respuesta->assertStatus(201)
        ->assertJsonFragment(['name' => 'Ficha de Monitoreo de Agua']);

    $this->assertDatabaseHas('worksheets', [
        'company_id'    => $this->empresa->id,
        'obligation_id' => $this->obligacion->id,
    ]);
});

test('test_listar_fichas_filtradas_por_obligacion', function (): void {
    // Ficha vinculada a la obligacion de prueba
    Worksheet::factory()->create([
        'company_id'    => $this->empresa->id,
        'obligation_id' => $this->obligacion->id,
    ]);

    // Otra obligacion con su ficha — no debe aparecer en el filtro
    $otraObligacion = Obligation::factory()->create(['company_id' => $this->empresa->id]);
    Worksheet::factory()->create([
        'company_id'    => $this->empresa->id,
        'obligation_id' => $otraObligacion->id,
    ]);

    $this->actingAs($this->coordinator, 'sanctum');

    $respuesta = $this->getJson("/api/v1/worksheets?obligation_id={$this->obligacion->id}");

    $respuesta->assertStatus(200);
    expect(count($respuesta->json('data')))->toBe(1);
});

test('test_listar_fichas_requiere_autenticacion', function (): void {
    $this->getJson('/api/v1/worksheets')
        ->assertStatus(401);
});

test('test_crear_ficha_sin_empresa_falla', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $this->postJson('/api/v1/worksheets', [
        'name' => 'Ficha sin empresa',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['company_id']);
});
