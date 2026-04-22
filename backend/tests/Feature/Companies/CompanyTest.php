<?php

use App\Models\Company;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests de Empresas (CRUD /api/v1/companies)
|--------------------------------------------------------------------------
|
| Nota: solo super_admin puede crear/eliminar empresas (ver CompanyPolicy).
| El rol admin puede ver y actualizar su propia empresa.
|
*/

beforeEach(function (): void {
    Mail::fake();

    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'coordinator', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'viewer',      'guard_name' => 'web']);

    $this->tipoDoc = DocumentType::factory()->create();

    // Empresa y admin propios del usuario autenticado
    $this->empresa = Company::factory()->create();
    $this->admin   = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $this->admin->assignRole('admin');

    // superAdmin para operaciones que requieren ese rol
    $this->superAdmin = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $this->superAdmin->assignRole('super_admin');
});

test('test_listar_empresas_requiere_autenticacion', function (): void {
    $this->getJson('/api/v1/companies')
        ->assertStatus(401);
});

test('test_listar_empresas_retorna_paginacion', function (): void {
    // Crear empresas adicionales
    Company::factory()->count(3)->create();

    $this->actingAs($this->admin, 'sanctum');

    $respuesta = $this->getJson('/api/v1/companies');

    $respuesta->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'meta' => ['current_page', 'total', 'per_page'],
        ]);
});

test('test_crear_empresa_con_datos_validos', function (): void {
    $this->actingAs($this->superAdmin, 'sanctum');

    $datos = [
        'name'             => 'Empresa de Prueba SAS',
        'nit'              => '900999888-1',
        'ciiu_code'        => '0110',
        'ciiu_description' => 'Cultivos de cereales',
        'phone'            => '3001234567',
        'email'            => 'contacto@empresaprueba.com',
        'address'          => 'Calle 100 # 15-30',
        'is_active'        => true,
        'admin_first_name' => 'Juan',
        'admin_last_name'  => 'Perez',
        'admin_email'      => 'juan.perez@empresaprueba.com',
        'admin_phone'      => '3109876543',
    ];

    $respuesta = $this->postJson('/api/v1/companies', $datos);

    $respuesta->assertStatus(201)
        ->assertJsonFragment(['name' => 'Empresa de Prueba SAS'])
        ->assertJsonFragment(['nit'  => '900999888-1']);

    $this->assertDatabaseHas('companies', ['nit' => '900999888-1']);
});

test('test_crear_empresa_con_nit_duplicado_falla', function (): void {
    $this->actingAs($this->superAdmin, 'sanctum');

    $datos = [
        'name'             => 'Empresa Duplicada',
        'nit'              => $this->empresa->nit, // NIT ya existe
        'ciiu_code'        => '0110',
        'ciiu_description' => 'Descripcion',
        'admin_first_name' => 'Maria',
        'admin_last_name'  => 'Lopez',
        'admin_email'      => 'maria@nueva.com',
    ];

    $this->postJson('/api/v1/companies', $datos)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['nit']);
});

test('test_ver_empresa_propia_retorna_datos', function (): void {
    $this->actingAs($this->admin, 'sanctum');

    $respuesta = $this->getJson("/api/v1/companies/{$this->empresa->id}");

    $respuesta->assertStatus(200)
        ->assertJsonFragment(['id' => $this->empresa->id]);
});

test('test_ver_empresa_de_otra_empresa_falla', function (): void {
    // Crear otra empresa completamente separada
    $otraEmpresa = Company::factory()->create();

    $this->actingAs($this->admin, 'sanctum');

    // El admin solo puede ver su propia empresa
    $this->getJson("/api/v1/companies/{$otraEmpresa->id}")
        ->assertStatus(403);
});

test('test_actualizar_empresa_propia', function (): void {
    $this->actingAs($this->admin, 'sanctum');

    $datos = [
        'name'             => 'Nombre Actualizado SAS',
        'nit'              => $this->empresa->nit,
        'ciiu_code'        => $this->empresa->ciiu_code,
        'ciiu_description' => $this->empresa->ciiu_description,
        'is_active'        => true,
    ];

    $respuesta = $this->putJson("/api/v1/companies/{$this->empresa->id}", $datos);

    $respuesta->assertStatus(200)
        ->assertJsonFragment(['name' => 'Nombre Actualizado SAS']);

    $this->assertDatabaseHas('companies', ['name' => 'Nombre Actualizado SAS']);
});

test('test_eliminar_empresa', function (): void {
    $this->actingAs($this->superAdmin, 'sanctum');

    $empresaAEliminar = Company::factory()->create();

    $this->deleteJson("/api/v1/companies/{$empresaAEliminar->id}")
        ->assertStatus(204);

    $this->assertSoftDeleted('companies', ['id' => $empresaAEliminar->id]);
});
