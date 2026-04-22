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
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests Unitarios - PurgeOldEvidencesCommand
|--------------------------------------------------------------------------
|
| Verifica el comportamiento del comando artisan evidences:purge:
| - dry-run: NO elimina nada
| - sin dry-run: elimina evidencias antiguas (forceDelete)
|
*/

beforeEach(function (): void {
    Storage::fake('local');

    Role::firstOrCreate(['name' => 'analyst', 'guard_name' => 'web']);

    $tipoDoc   = DocumentType::factory()->create();
    $empresa   = Company::factory()->create();
    $frecuencia = IndicatorFrequency::factory()->create();

    $obligacion = Obligation::factory()->create(['company_id' => $empresa->id]);
    $ficha      = Worksheet::factory()->create([
        'company_id'    => $empresa->id,
        'obligation_id' => $obligacion->id,
    ]);
    $indicador  = Indicator::factory()->create([
        'company_id'             => $empresa->id,
        'worksheet_id'           => $ficha->id,
        'indicator_frequency_id' => $frecuencia->id,
    ]);
    $actividad  = Activity::factory()->create([
        'company_id'   => $empresa->id,
        'indicator_id' => $indicador->id,
    ]);
    $usuario    = User::factory()->create([
        'company_id'       => $empresa->id,
        'document_type_id' => $tipoDoc->id,
    ]);

    // Evidencia ANTIGUA: creada hace 6 anos (supera el umbral de 5 anos)
    $this->evidenciaAntigua = Evidence::factory()->create([
        'company_id'   => $empresa->id,
        'activity_id'  => $actividad->id,
        'uploaded_by'  => $usuario->id,
        'storage_path' => 'evidences/test/antigua.pdf',
        'created_at'   => now()->subYears(6),
        'updated_at'   => now()->subYears(6),
    ]);

    // Simular que el archivo existe en el storage falso
    Storage::disk('local')->put('evidences/test/antigua.pdf', 'contenido de prueba');

    // Evidencia RECIENTE: no debe ser eliminada
    $this->evidenciaReciente = Evidence::factory()->create([
        'company_id'   => $empresa->id,
        'activity_id'  => $actividad->id,
        'uploaded_by'  => $usuario->id,
        'storage_path' => 'evidences/test/reciente.pdf',
        'created_at'   => now()->subMonths(1),
        'updated_at'   => now()->subMonths(1),
    ]);

    Storage::disk('local')->put('evidences/test/reciente.pdf', 'contenido reciente');
});

test('test_dry_run_no_elimina_archivos', function (): void {
    $this->artisan('evidences:purge', ['--dry-run' => true])
        ->assertExitCode(0);

    // La evidencia antigua debe seguir existiendo (no se elimino)
    $this->assertDatabaseHas('evidences', [
        'id' => $this->evidenciaAntigua->id,
    ]);

    // El archivo fisico tampoco debe haberse eliminado
    Storage::disk('local')->assertExists('evidences/test/antigua.pdf');
});

test('test_purge_elimina_evidencias_antiguas', function (): void {
    $this->artisan('evidences:purge')
        ->assertExitCode(0);

    // La evidencia antigua debe haber sido eliminada permanentemente (forceDelete)
    $this->assertDatabaseMissing('evidences', [
        'id' => $this->evidenciaAntigua->id,
    ]);

    // El archivo fisico debe haber sido eliminado del storage
    Storage::disk('local')->assertMissing('evidences/test/antigua.pdf');
});

test('test_purge_no_elimina_evidencias_recientes', function (): void {
    $this->artisan('evidences:purge')
        ->assertExitCode(0);

    // La evidencia reciente NO debe haber sido eliminada
    $this->assertDatabaseHas('evidences', [
        'id' => $this->evidenciaReciente->id,
    ]);

    Storage::disk('local')->assertExists('evidences/test/reciente.pdf');
});
