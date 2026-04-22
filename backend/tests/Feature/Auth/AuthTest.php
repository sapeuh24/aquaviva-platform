<?php

use App\Models\Company;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Tests de Autenticacion
|--------------------------------------------------------------------------
|
| Cubre: POST /api/v1/auth/login
|        POST /api/v1/auth/logout
|        GET  /api/v1/auth/me
|
*/

beforeEach(function (): void {
    // Crear roles necesarios para Spatie permission
    \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    // Preparar usuario de prueba
    $empresa = Company::factory()->create();
    $tipo    = DocumentType::factory()->create();

    $this->usuario = User::factory()->create([
        'company_id'      => $empresa->id,
        'document_type_id' => $tipo->id,
        'email'           => 'usuario@test.com',
        'password'        => Hash::make('password123'),
        'is_active'       => true,
    ]);
});

test('test_login_con_credenciales_validas_retorna_token', function (): void {
    $respuesta = $this->postJson('/api/v1/auth/login', [
        'email'    => 'usuario@test.com',
        'password' => 'password123',
    ]);

    $respuesta
        ->assertStatus(200)
        ->assertJsonStructure([
            'token',
            'user' => ['id', 'email'],
        ]);

    expect($respuesta->json('token'))->not->toBeEmpty();
});

test('test_login_con_credenciales_invalidas_retorna_422', function (): void {
    $respuesta = $this->postJson('/api/v1/auth/login', [
        'email'    => 'usuario@test.com',
        'password' => 'password_incorrecta',
    ]);

    $respuesta->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('test_login_con_email_inexistente_retorna_422', function (): void {
    $respuesta = $this->postJson('/api/v1/auth/login', [
        'email'    => 'noexiste@test.com',
        'password' => 'password123',
    ]);

    $respuesta->assertStatus(422);
});

test('test_logout_invalida_el_token', function (): void {
    // Crear token de Sanctum directamente (PersonalAccessToken real en DB)
    $plainToken = $this->usuario->createToken('test-token')->plainTextToken;
    $tokenId    = (int) explode('|', $plainToken)[0];

    // Logout con el token como Bearer
    $this->withToken($plainToken)
        ->postJson('/api/v1/auth/logout')
        ->assertStatus(204);

    // El token debe haber sido eliminado de la base de datos
    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId]);
});

test('test_me_retorna_usuario_autenticado', function (): void {
    $this->actingAs($this->usuario, 'sanctum');

    $respuesta = $this->getJson('/api/v1/auth/me');

    $respuesta
        ->assertStatus(200)
        ->assertJsonFragment([
            'email' => 'usuario@test.com',
        ]);
});

test('test_me_sin_autenticacion_retorna_401', function (): void {
    $respuesta = $this->getJson('/api/v1/auth/me');

    $respuesta->assertStatus(401);
});
