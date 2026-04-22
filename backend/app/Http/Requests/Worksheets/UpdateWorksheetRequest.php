<?php

namespace App\Http\Requests\Worksheets;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorksheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id'       => ['required', 'integer', 'exists:companies,id'],
            'obligation_id'    => ['nullable', 'integer', 'exists:obligations,id'],
            'monitoring_id'    => ['nullable', 'integer', 'exists:monitorings,id'],
            'monitoring_tool'  => ['nullable', 'string', 'max:200'],
            'monitoring_phase' => ['nullable', 'string', 'max:100'],
            'name'             => ['nullable', 'string', 'max:200'],
            'objective'        => ['nullable', 'string'],
            'target'           => ['nullable', 'string'],
            'observations'     => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required'    => 'La empresa es obligatoria.',
            'company_id.exists'      => 'La empresa seleccionada no existe.',
            'obligation_id.exists'   => 'La obligacion seleccionada no existe.',
            'monitoring_id.exists'   => 'La ficha PMA seleccionada no existe.',
            'monitoring_tool.max'    => 'La herramienta de monitoreo no puede superar 200 caracteres.',
            'monitoring_phase.max'   => 'La fase de monitoreo no puede superar 100 caracteres.',
            'name.max'               => 'El nombre no puede superar 200 caracteres.',
        ];
    }
}
