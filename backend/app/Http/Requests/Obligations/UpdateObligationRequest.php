<?php

namespace App\Http\Requests\Obligations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateObligationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id'                 => ['required', 'integer', 'exists:companies,id'],
            'monitoring_id'              => ['nullable', 'integer', 'exists:monitorings,id'],
            'environmental_authority_id' => ['nullable', 'integer', 'exists:environmental_authorities,id'],
            'resolution_number'          => ['nullable', 'string', 'max:100'],
            'resolution_date'            => ['nullable', 'date'],
            'instrument_type'            => ['nullable', 'string', 'max:150'],
            'name'                       => ['required', 'string', 'max:300'],
            'description'                => ['nullable', 'string'],
            'legal_basis'                => ['nullable', 'string'],
            'environmental_medium'       => ['nullable', 'string', 'max:150'],
            'obligation_type'            => ['nullable', 'string', 'max:150'],
            'compliance_deadline'        => ['nullable', 'date'],
            'compliance_frequency'       => ['nullable', 'string', 'max:100'],
            'status'                     => ['string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required'               => 'La empresa es obligatoria.',
            'company_id.exists'                 => 'La empresa seleccionada no existe.',
            'monitoring_id.exists'              => 'La ficha PMA seleccionada no existe.',
            'environmental_authority_id.exists' => 'La autoridad ambiental seleccionada no existe.',
            'resolution_number.max'             => 'El numero de resolucion no puede superar 100 caracteres.',
            'resolution_date.date'              => 'La fecha de resolucion no tiene un formato valido.',
            'instrument_type.max'               => 'El tipo de instrumento no puede superar 150 caracteres.',
            'name.required'                     => 'El nombre de la obligacion es obligatorio.',
            'name.max'                          => 'El nombre no puede superar 300 caracteres.',
            'environmental_medium.max'          => 'El medio ambiental no puede superar 150 caracteres.',
            'obligation_type.max'               => 'El tipo de obligacion no puede superar 150 caracteres.',
            'compliance_deadline.date'          => 'La fecha limite de cumplimiento no tiene un formato valido.',
            'compliance_frequency.max'          => 'La frecuencia de cumplimiento no puede superar 100 caracteres.',
        ];
    }
}
