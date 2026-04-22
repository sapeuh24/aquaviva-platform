<?php

use App\Models\Company;
use App\Models\DocumentType;
use App\Models\Indicator;
use App\Models\IndicatorFrequency;
use App\Models\Obligation;
use App\Models\User;
use App\Models\Worksheet;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests de Indicadores (CRUD /api/v1/indicators)
|--------------------------------------------------------------------------
*/

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'coordinator', 'guard_name' => 'web']);

    $this->tipoDoc   = DocumentType::factory()->create();
    $this->empresa   = Company::factory()->create();
    $this->frecuencia = IndicatorFrequency::factory()->create();

    $obligacion = Obligation::factory()->create(['company_id' => $this->empresa->id]);

    $this->ficha = Worksheet::factory()->create([
        'company_id'    => $this->empresa->id,
        'obligation_id' => $obligacion->id,
    ]);

    $this->coordinator = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $this->coordinator->assignRole('coordinator');
});

test('test_crear_indicador', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $datos = [
        'worksheet_id'           => $this->ficha->id,
        'indicator_frequency_id' => $this->frecuencia->id,
        'name'                   => 'Porcentaje de cumplimiento de tratamiento de agua',
        'objective'              => 'Medir eficiencia del sistema PTAR',
        'target'                 => 'Mayor al 85%',
        'measurement_unit'       => '%',
        'baseline_value'         => 60.0,
        'target_value'           => 85.0,
        'next_due_date'          => '2024-06-30',
        'is_active'              => true,
    ];

    $respuesta = $this->postJson('/api/v1/indicators', $datos);

    $respuesta->assertStatus(201)
        ->assertJsonFragment(['worksheet_id' => $this->ficha->id]);

    $this->assertDatabaseHas('indicators', [
        'worksheet_id' => $this->ficha->id,
        'is_active'    => true,
    ]);
});

test('test_listar_indicadores_activos', function (): void {
    // Indicador activo
    Indicator::factory()->create([
        'company_id'             => $this->empresa->id,
        'worksheet_id'           => $this->ficha->id,
        'indicator_frequency_id' => $this->frecuencia->id,
        'is_active'              => true,
    ]);

    // Indicador inactivo — no debe aparecer en el filtro
    Indicator::factory()->create([
        'company_id'             => $this->empresa->id,
        'worksheet_id'           => $this->ficha->id,
        'indicator_frequency_id' => $this->frecuencia->id,
        'is_active'              => false,
    ]);

    $this->actingAs($this->coordinator, 'sanctum');

    $respuesta = $this->getJson('/api/v1/indicators?is_active=true');

    $respuesta->assertStatus(200);
    expect(count($respuesta->json('data')))->toBe(1);
});

test('test_listar_indicadores_requiere_autenticacion', function (): void {
    $this->getJson('/api/v1/indicators')
        ->assertStatus(401);
});

test('test_crear_indicador_sin_ficha_falla', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $this->postJson('/api/v1/indicators', [
        'name'                   => 'Indicador huerfano',
        'indicator_frequency_id' => $this->frecuencia->id,
        // falta worksheet_id
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['worksheet_id']);
});
