<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? $this->route('user');

        return [
            'company_id'       => ['required', 'integer', 'exists:companies,id'],
            'document_type_id' => ['required', 'integer', 'exists:document_types,id'],
            'document_number'  => ['required', 'string', 'max:30'],
            'first_name'       => ['required', 'string', 'max:100'],
            'last_name'        => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($userId)],
            'phone'            => ['nullable', 'string', 'max:20'],
            'password'         => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active'        => ['boolean'],
            'roles'            => ['array'],
            'roles.*'          => ['string', 'exists:roles,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required'       => 'La empresa es obligatoria.',
            'company_id.exists'         => 'La empresa seleccionada no existe.',
            'document_type_id.required' => 'El tipo de documento es obligatorio.',
            'document_type_id.exists'   => 'El tipo de documento seleccionado no existe.',
            'document_number.required'  => 'El numero de documento es obligatorio.',
            'document_number.max'       => 'El numero de documento no puede superar 30 caracteres.',
            'first_name.required'       => 'El nombre es obligatorio.',
            'first_name.max'            => 'El nombre no puede superar 100 caracteres.',
            'last_name.required'        => 'El apellido es obligatorio.',
            'last_name.max'             => 'El apellido no puede superar 100 caracteres.',
            'email.required'            => 'El correo electronico es obligatorio.',
            'email.email'               => 'El correo electronico no tiene un formato valido.',
            'email.unique'              => 'El correo electronico ya esta registrado en otro usuario.',
            'phone.max'                 => 'El telefono no puede superar 20 caracteres.',
            'password.min'              => 'La contrasena debe tener al menos 8 caracteres.',
            'password.confirmed'        => 'Las contrasenas no coinciden.',
            'roles.array'               => 'Los roles deben ser un arreglo.',
            'roles.*.exists'            => 'Uno o mas roles seleccionados no existen.',
        ];
    }
}
