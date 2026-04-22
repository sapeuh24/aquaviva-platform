<?php

namespace App\Http\Requests\OrganizationProjects;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id'      => ['required', 'integer', 'exists:companies,id'],
            'municipality_id' => ['nullable', 'integer', 'exists:municipalities,id'],
            'name'            => ['required', 'string', 'max:200'],
            'code'            => ['nullable', 'string', 'max:50'],
            'description'     => ['nullable', 'string'],
            'location'        => ['nullable', 'string', 'max:300'],
            'start_date'      => ['nullable', 'date'],
            'end_date'        => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'          => ['string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required'      => 'La empresa es obligatoria.',
            'company_id.exists'        => 'La empresa seleccionada no existe.',
            'municipality_id.exists'   => 'El municipio seleccionado no existe.',
            'name.required'            => 'El nombre del proyecto es obligatorio.',
            'name.max'                 => 'El nombre no puede superar 200 caracteres.',
            'code.max'                 => 'El codigo no puede superar 50 caracteres.',
            'location.max'             => 'La ubicacion no puede superar 300 caracteres.',
            'start_date.date'          => 'La fecha de inicio no tiene un formato valido.',
            'end_date.date'            => 'La fecha de cierre no tiene un formato valido.',
            'end_date.after_or_equal'  => 'La fecha de cierre debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}
