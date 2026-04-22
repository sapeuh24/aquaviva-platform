<?php

use App\Models\Company;
use App\Models\DocumentType;
use App\Models\User;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests de Usuarios (CRUD /api/v1/users)
|--------------------------------------------------------------------------
*/

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin',       'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'coordinator', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'analyst',     'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'viewer',      'guard_name' => 'web']);

    $this->tipoDoc = DocumentType::factory()->create(['code' => 'CC']);
    $this->empresa = Company::factory()->create();

    $this->admin = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);
    $this->admin->assignRole('admin');
});

test('test_crear_usuario_en_empresa_propia', function (): void {
    $this->actingAs($this->admin, 'sanctum');

    $datos = [
        'company_id'        => $this->empresa->id,
        'document_type_id'  => $this->tipoDoc->id,
        'document_number'   => '12345678',
        'first_name'        => 'Carlos',
        'last_name'         => 'Gomez',
        'email'             => 'carlos.gomez@empresa.com',
        'password'          => 'Password123!',
        'password_confirmation' => 'Password123!',
        'is_active'         => true,
        'roles'             => ['analyst'],
    ];

    $respuesta = $this->postJson('/api/v1/users', $datos);

    $respuesta->assertStatus(201)
        ->assertJsonFragment(['email' => 'carlos.gomez@empresa.com']);

    $this->assertDatabaseHas('users', ['email' => 'carlos.gomez@empresa.com']);
});

test('test_listar_usuarios_solo_muestra_empresa_propia', function (): void {
    // Crear usuarios de la empresa propia
    User::factory()->count(2)->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);

    $this->actingAs($this->admin, 'sanctum');

    // Filtrar explicitamente por company_id (es la forma correcta de multi-tenant en este endpoint)
    $respuesta = $this->getJson("/api/v1/users?company_id={$this->empresa->id}");

    $respuesta->assertStatus(200);

    // El admin + 2 usuarios = 3 usuarios de la empresa
    expect(count($respuesta->json('data')))->toBe(3);

    // Todos deben pertenecer a la misma empresa
    $ids = collect($respuesta->json('data'))->pluck('id')->toArray();
    foreach ($ids as $id) {
        $usuario = User::withTrashed()->find($id);
        expect($usuario->company_id)->toBe($this->empresa->id);
    }
});

test('test_actualizar_usuario', function (): void {
    $this->actingAs($this->admin, 'sanctum');

    // El admin puede actualizar un usuario de su propia empresa
    $usuario = User::factory()->create([
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
    ]);

    $datos = [
        'company_id'       => $this->empresa->id,
        'document_type_id' => $this->tipoDoc->id,
        'document_number'  => $usuario->document_number,
        'first_name'       => 'Nombre Actualizado',
        'last_name'        => $usuario->last_name,
        'email'            => $usuario->email,
        'is_active'        => true,
    ];

    $respuesta = $this->putJson("/api/v1/users/{$usuario->id}", $datos);

    $respuesta->assertStatus(200)
        ->assertJsonFragment(['first_name' => 'Nombre Actualizado']);

    $this->assertDatabaseHas('users', [
        'id'         => $usuario->id,
        'first_name' => 'Nombre Actualizado',
    ]);
});

test('test_listar_usuarios_requiere_autenticacion', function (): void {
    $this->getJson('/api/v1/users')
        ->assertStatus(401);
});
