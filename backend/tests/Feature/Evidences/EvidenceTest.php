<?php

use App\Models\Activity;
use App\Models\Company;
use App\Models\DocumentType;
use App\Models\Evidence;
use App\Models\Indicator;
use App\Models\IndicatorFrequency;
use App\Models\Obligation;
use App\Models\User;
use App\Models\Worksheet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests de Evidencias (CRUD /api/v1/evidences)
|--------------------------------------------------------------------------
*/

beforeEach(function (): void {
    Storage::fake('local');

    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'coordinator', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'analyst',     'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'viewer',      'guard_name' => 'web']);

    $this->tipoDoc   = DocumentType::factory()->create();
    $this->empresa   = Company::factory()->create();
    $this->frecuencia = IndicatorFrequency::factory()->create();

    $obligacion = Obligation::factory()->create(['company_id' => $this->empresa->id]);
    $ficha      = Worksheet::factory()->create([
        'company_id'    => $this->empresa->id,
        'obligation_id' => $obligacion->id,
    ]);
    $indicador  = Indicator::factory()->create([
        'company_id'             => $this->empresa->id,
        'worksheet_id'           => $ficha->id,
        'indicator_frequency_id' => $this->frecuencia->id,
    ]);

    $this->actividad = Activity::factory()->create([
        'company_id'   => $this->empresa->id,
        'indicator_id' => $indicador->id,
    ]);

    $this->analista = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $this->analista->assignRole('analyst');
});

test('test_subir_evidencia_retorna_201', function (): void {
    $this->actingAs($this->analista, 'sanctum');

    $archivo = UploadedFile::fake()->create(
        name:      'reporte_agua.pdf',
        kilobytes: 512,
        mimeType:  'application/pdf',
    );

    $respuesta = $this->postJson('/api/v1/evidences', [
        'file'        => $archivo,
        'activity_id' => $this->actividad->id,
        'description' => 'Reporte de calidad de agua Q1 2024',
    ]);

    $respuesta->assertStatus(201)
        ->assertJsonStructure([
            'data' => ['id', 'original_name', 'mime_type', 'size_bytes'],
        ]);

    $this->assertDatabaseHas('evidences', [
        'activity_id' => $this->actividad->id,
        'uploaded_by' => $this->analista->id,
        'mime_type'   => 'application/pdf',
    ]);
});

test('test_subir_archivo_tipo_invalido_falla', function (): void {
    $this->actingAs($this->analista, 'sanctum');

    // Archivo ejecutable — tipo no permitido
    $archivo = UploadedFile::fake()->create(
        name:      'malware.exe',
        kilobytes: 100,
        mimeType:  'application/x-msdownload',
    );

    $this->postJson('/api/v1/evidences', [
        'file'        => $archivo,
        'activity_id' => $this->actividad->id,
    ])->assertStatus(422);
});

test('test_solo_uploader_puede_eliminar_evidencia', function (): void {
    $this->actingAs($this->analista, 'sanctum');

    // El propio analista sube la evidencia
    $evidencia = Evidence::factory()->create([
        'company_id'    => $this->empresa->id,
        'activity_id'   => $this->actividad->id,
        'uploaded_by'   => $this->analista->id,
        'storage_path'  => 'evidences/test/archivo.pdf',
    ]);

    $respuesta = $this->deleteJson("/api/v1/evidences/{$evidencia->id}");

    $respuesta->assertStatus(204);

    $this->assertSoftDeleted('evidences', ['id' => $evidencia->id]);
});

test('test_otro_usuario_no_puede_eliminar_evidencia_ajena', function (): void {
    // Crear otro analista de la misma empresa
    $otroAnalista = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $otroAnalista->assignRole('analyst');

    // La evidencia fue subida por el primer analista
    $evidencia = Evidence::factory()->create([
        'company_id'  => $this->empresa->id,
        'activity_id' => $this->actividad->id,
        'uploaded_by' => $this->analista->id,
    ]);

    // El segundo analista intenta eliminarla
    $this->actingAs($otroAnalista, 'sanctum');

    $this->deleteJson("/api/v1/evidences/{$evidencia->id}")
        ->assertStatus(403);

    // La evidencia debe seguir existiendo sin eliminar
    expect(Evidence::find($evidencia->id))->not->toBeNull();
});

test('test_listar_evidencias_requiere_autenticacion', function (): void {
    $this->getJson('/api/v1/evidences')
        ->assertStatus(401);
});
