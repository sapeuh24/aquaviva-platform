<?php

use App\Actions\Auth\LoginAction;
use App\DataTransferObjects\Auth\LoginData;
use App\Models\Company;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Tests Unitarios - LoginAction
|--------------------------------------------------------------------------
|
| Estos tests usan la DB SQLite en memoria (sin mocks de repositorio)
| ya que LoginAction depende de Auth::attempt() que requiere consultas reales.
|
*/

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    $tipoDoc = DocumentType::factory()->create();
    $empresa = Company::factory()->create();

    $this->usuario = User::factory()->create([
        'company_id'       => $empresa->id,
        'document_type_id' => $tipoDoc->id,
        'email'            => 'login@test.com',
        'password'         => Hash::make('password_correcto'),
        'is_active'        => true,
    ]);
});

test('test_login_exitoso_retorna_token_y_usuario', function (): void {
    $action = app(LoginAction::class);

    $data = new LoginData(
        email:    'login@test.com',
        password: 'password_correcto',
    );

    $resultado = $action->execute($data);

    expect($resultado)
        ->toHaveKey('token')
        ->toHaveKey('user');

    expect($resultado['token'])->not->toBeEmpty();
    expect($resultado['user'])->toBeInstanceOf(User::class);
    expect($resultado['user']->email)->toBe('login@test.com');
});

test('test_login_con_password_incorrecto_lanza_validation_exception', function (): void {
    $action = app(LoginAction::class);

    $data = new LoginData(
        email:    'login@test.com',
        password: 'password_incorrecto',
    );

    expect(fn () => $action->execute($data))
        ->toThrow(ValidationException::class);
});

test('test_login_con_email_inexistente_lanza_validation_exception', function (): void {
    $action = app(LoginAction::class);

    $data = new LoginData(
        email:    'noexiste@test.com',
        password: 'password_correcto',
    );

    expect(fn () => $action->execute($data))
        ->toThrow(ValidationException::class);
});
