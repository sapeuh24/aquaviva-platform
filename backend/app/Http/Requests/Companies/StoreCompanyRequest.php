<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Datos de la empresa
            'name'             => ['required', 'string', 'max:200'],
            'nit'              => ['required', 'string', 'max:20', 'unique:companies,nit'],
            'ciiu_code'        => ['required', 'string', 'max:20'],
            'ciiu_description' => ['required', 'string', 'max:255'],
            'municipality_id'  => ['nullable', 'integer', 'exists:municipalities,id'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'email'            => ['nullable', 'email', 'max:150', 'unique:companies,email'],
            'address'          => ['nullable', 'string', 'max:300'],
            'is_active'        => ['boolean'],

            // Datos del usuario administrador
            'admin_first_name' => ['required', 'string', 'max:100'],
            'admin_last_name'  => ['required', 'string', 'max:100'],
            'admin_email'      => ['required', 'email', 'max:150', 'unique:users,email'],
            'admin_phone'      => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            // Empresa
            'name.required'             => 'La razón social es obligatoria.',
            'name.max'                  => 'La razón social no puede superar 200 caracteres.',
            'nit.required'              => 'El NIT es obligatorio.',
            'nit.max'                   => 'El NIT no puede superar 20 caracteres.',
            'nit.unique'                => 'El NIT ingresado ya está registrado.',
            'ciiu_code.required'        => 'El código CIIU es obligatorio.',
            'ciiu_code.max'             => 'El código CIIU no puede superar 20 caracteres.',
            'ciiu_description.required' => 'La descripción de la actividad CIIU es obligatoria.',
            'ciiu_description.max'      => 'La descripción CIIU no puede superar 255 caracteres.',
            'municipality_id.exists'    => 'El municipio seleccionado no existe.',
            'phone.max'                 => 'El teléfono no puede superar 20 caracteres.',
            'email.email'               => 'El correo electrónico de la empresa no tiene un formato válido.',
            'email.unique'              => 'El correo electrónico de la empresa ya está registrado.',
            'address.max'               => 'La dirección no puede superar 300 caracteres.',

            // Administrador
            'admin_first_name.required' => 'El nombre del administrador es obligatorio.',
            'admin_first_name.string'   => 'El nombre del administrador debe ser texto.',
            'admin_first_name.max'      => 'El nombre del administrador no puede superar 100 caracteres.',
            'admin_last_name.required'  => 'El apellido del administrador es obligatorio.',
            'admin_last_name.string'    => 'El apellido del administrador debe ser texto.',
            'admin_last_name.max'       => 'El apellido del administrador no puede superar 100 caracteres.',
            'admin_email.required'      => 'El correo electrónico del administrador es obligatorio.',
            'admin_email.email'         => 'El correo electrónico del administrador no tiene un formato válido.',
            'admin_email.unique'        => 'El correo electrónico del administrador ya está registrado en el sistema.',
            'admin_phone.max'           => 'El teléfono del administrador no puede superar 20 caracteres.',
        ];
    }
}
