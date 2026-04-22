<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'El correo electronico es obligatorio.',
            'email.email'       => 'El correo electronico no tiene un formato valido.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.string'   => 'La contrasena debe ser texto.',
            'password.min'      => 'La contrasena debe tener al menos 8 caracteres.',
        ];
    }
}
