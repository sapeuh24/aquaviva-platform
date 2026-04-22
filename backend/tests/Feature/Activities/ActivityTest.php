<?php

use App\Models\Activity;
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
| Tests de Actividades (CRUD /api/v1/activities)
|--------------------------------------------------------------------------
*/

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'coordinator', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'analyst',     'guard_name' => 'web']);

    $this->tipoDoc   = DocumentType::factory()->create();
    $this->empresa   = Company::factory()->create();
    $this->frecuencia = IndicatorFrequency::factory()->create();

    $obligacion  = Obligation::factory()->create(['company_id' => $this->empresa->id]);
    $ficha       = Worksheet::factory()->create([
        'company_id'    => $this->empresa->id,
        'obligation_id' => $obligacion->id,
    ]);

    $this->indicador = Indicator::factory()->create([
        'company_id'             => $this->empresa->id,
        'worksheet_id'           => $ficha->id,
        'indicator_frequency_id' => $this->frecuencia->id,
    ]);

    // coordinator puede crear, asignar y actualizar actividades
    $this->coordinator = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $this->coordinator->assignRole('coordinator');

    // analyst puede ver y crear evidencias pero solo actualizar si es asignado
    $this->analyst = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $this->analyst->assignRole('analyst');
});

test('test_crear_actividad', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $datos = [
        'indicator_id'      => $this->indicador->id,
        'name'              => 'Toma de muestras de agua en punto P1',
        'description'       => 'Muestra de agua en el rio Bogota sector norte',
        'scheduled_date'    => '2024-05-15',
        'compliance_status' => 'pending',
    ];

    $respuesta = $this->postJson('/api/v1/activities', $datos);

    $respuesta->assertStatus(201)
        ->assertJsonFragment(['indicator_id' => $this->indicador->id]);

    $this->assertDatabaseHas('activities', [
        'indicator_id' => $this->indicador->id,
        'compliance_status' => 'pending',
    ]);
});

test('test_asignar_usuario_a_actividad', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $actividad = Activity::factory()->create([
        'company_id'   => $this->empresa->id,
        'indicator_id' => $this->indicador->id,
    ]);

    $respuesta = $this->postJson(
        "/api/v1/activities/{$actividad->id}/users/{$this->analyst->id}"
    );

    $respuesta->assertStatus(200)
        ->assertJsonFragment(['message' => 'Usuario asignado correctamente.']);

    $this->assertDatabaseHas('activity_user', [
        'activity_id' => $actividad->id,
        'user_id'     => $this->analyst->id,
    ]);
});

test('test_desasignar_usuario_de_actividad', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $actividad = Activity::factory()->create([
        'company_id'   => $this->empresa->id,
        'indicator_id' => $this->indicador->id,
    ]);

    // Asignar primero
    $actividad->users()->attach($this->analyst->id);

    $respuesta = $this->deleteJson(
        "/api/v1/activities/{$actividad->id}/users/{$this->analyst->id}"
    );

    $respuesta->assertStatus(204);

    $this->assertDatabaseMissing('activity_user', [
        'activity_id' => $actividad->id,
        'user_id'     => $this->analyst->id,
    ]);
});

test('test_actualizar_estado_cumplimiento', function (): void {
    $this->actingAs($this->coordinator, 'sanctum');

    $actividad = Activity::factory()->create([
        'company_id'        => $this->empresa->id,
        'indicator_id'      => $this->indicador->id,
        'compliance_status' => 'pending',
    ]);

    $datos = [
        'indicator_id'      => $this->indicador->id,
        'name'              => $actividad->name,
        'compliance_status' => 'completed',
        'executed_date'     => now()->toDateString(),
    ];

    $respuesta = $this->putJson("/api/v1/activities/{$actividad->id}", $datos);

    $respuesta->assertStatus(200);

    $this->assertDatabaseHas('activities', [
        'id'                => $actividad->id,
        'compliance_status' => 'completed',
    ]);
});

test('test_listar_actividades_requiere_autenticacion', function (): void {
    $this->getJson('/api/v1/activities')
        ->assertStatus(401);
});

test('test_analyst_no_puede_crear_actividad', function (): void {
    $this->actingAs($this->analyst, 'sanctum');

    $datos = [
        'indicator_id'      => $this->indicador->id,
        'name'              => 'Actividad no permitida',
        'compliance_status' => 'pending',
    ];

    $this->postJson('/api/v1/activities', $datos)
        ->assertStatus(403);
});
