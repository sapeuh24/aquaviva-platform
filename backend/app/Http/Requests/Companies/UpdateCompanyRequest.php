<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id ?? $this->route('company');

        return [
            'name'             => ['required', 'string', 'max:200'],
            'nit'              => ['required', 'string', 'max:20', Rule::unique('companies', 'nit')->ignore($companyId)],
            'ciiu_code'        => ['required', 'string', 'max:20'],
            'ciiu_description' => ['required', 'string', 'max:255'],
            'municipality_id'  => ['nullable', 'integer', 'exists:municipalities,id'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'email'            => ['nullable', 'email', 'max:150', Rule::unique('companies', 'email')->ignore($companyId)],
            'address'          => ['nullable', 'string', 'max:300'],
            'is_active'        => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'La razon social es obligatoria.',
            'name.max'                  => 'La razon social no puede superar 200 caracteres.',
            'nit.required'              => 'El NIT es obligatorio.',
            'nit.max'                   => 'El NIT no puede superar 20 caracteres.',
            'nit.unique'                => 'El NIT ingresado ya esta registrado en otra empresa.',
            'ciiu_code.required'        => 'El codigo CIIU es obligatorio.',
            'ciiu_code.max'             => 'El codigo CIIU no puede superar 20 caracteres.',
            'ciiu_description.required' => 'La descripcion de la actividad CIIU es obligatoria.',
            'ciiu_description.max'      => 'La descripcion CIIU no puede superar 255 caracteres.',
            'municipality_id.exists'    => 'El municipio seleccionado no existe.',
            'phone.max'                 => 'El telefono no puede superar 20 caracteres.',
            'email.email'               => 'El correo electronico no tiene un formato valido.',
            'email.unique'              => 'El correo electronico ya esta registrado en otra empresa.',
            'address.max'               => 'La direccion no puede superar 300 caracteres.',
        ];
    }
}
