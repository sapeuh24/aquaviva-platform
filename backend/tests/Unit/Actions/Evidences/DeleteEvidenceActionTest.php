<?php

use App\Actions\Evidences\DeleteEvidenceAction;
use App\Exceptions\BusinessException;
use App\Models\Company;
use App\Models\DocumentType;
use App\Models\Evidence;
use App\Models\User;
use App\Repositories\Contracts\EvidenceRepositoryInterface;
use App\Services\EvidenceUploadService;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests Unitarios - DeleteEvidenceAction
|--------------------------------------------------------------------------
|
| Verifica la regla de negocio: solo el usuario que subio la evidencia
| puede eliminarla. Usa Mockery para aislar repositorio y servicio.
|
*/

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'analyst', 'guard_name' => 'web']);

    $tipoDoc = DocumentType::factory()->create();
    $empresa = Company::factory()->create();

    $this->uploader = User::factory()->create([
        'company_id'       => $empresa->id,
        'document_type_id' => $tipoDoc->id,
    ]);
    $this->uploader->assignRole('analyst');

    $this->otroUsuario = User::factory()->create([
        'company_id'       => $empresa->id,
        'document_type_id' => $tipoDoc->id,
    ]);
    $this->otroUsuario->assignRole('analyst');

    // Evidencia que pertenece a $this->uploader
    $this->evidencia = Evidence::factory()->make([
        'id'          => 1,
        'uploaded_by' => $this->uploader->id,
        'storage_path' => 'evidences/test/archivo.pdf',
    ]);
});

test('test_uploader_puede_eliminar_su_evidencia', function (): void {
    $repositoryMock = Mockery::mock(EvidenceRepositoryInterface::class);
    $repositoryMock
        ->shouldReceive('softDelete')
        ->once()
        ->with($this->evidencia);

    $uploadServiceMock = Mockery::mock(EvidenceUploadService::class);
    $uploadServiceMock
        ->shouldReceive('delete')
        ->once()
        ->with($this->evidencia);

    $action = new DeleteEvidenceAction($repositoryMock, $uploadServiceMock);

    // No debe lanzar excepcion
    $action->execute($this->evidencia, $this->uploader);

    // Si llega aqui sin excepcion, el test pasa
    expect(true)->toBeTrue();
});

test('test_otro_usuario_no_puede_eliminar_evidencia', function (): void {
    $repositoryMock = Mockery::mock(EvidenceRepositoryInterface::class);
    $repositoryMock->shouldNotReceive('softDelete');

    $uploadServiceMock = Mockery::mock(EvidenceUploadService::class);
    $uploadServiceMock->shouldNotReceive('delete');

    $action = new DeleteEvidenceAction($repositoryMock, $uploadServiceMock);

    expect(fn () => $action->execute($this->evidencia, $this->otroUsuario))
        ->toThrow(BusinessException::class, 'Solo puedes eliminar archivos que tu has subido.');
});

test('test_excepcion_tiene_codigo_403', function (): void {
    $repositoryMock    = Mockery::mock(EvidenceRepositoryInterface::class);
    $uploadServiceMock = Mockery::mock(EvidenceUploadService::class);

    $action = new DeleteEvidenceAction($repositoryMock, $uploadServiceMock);

    try {
        $action->execute($this->evidencia, $this->otroUsuario);
        expect(false)->toBeTrue('Deberia haber lanzado excepcion');
    } catch (BusinessException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});

afterEach(function (): void {
    Mockery::close();
});
