<?php

namespace App\Http\Requests\Activities;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'indicator_id'      => ['required', 'integer', 'exists:indicators,id'],
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['nullable', 'string'],
            'scheduled_date'    => ['nullable', 'date'],
            'executed_date'     => ['nullable', 'date'],
            'compliance_status' => ['required', 'string', 'in:pending,in_progress,completed,not_completed'],
            'notes'             => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'indicator_id.required'      => 'El indicador es obligatorio.',
            'indicator_id.exists'        => 'El indicador seleccionado no existe.',
            'name.required'              => 'El nombre de la actividad es obligatorio.',
            'name.max'                   => 'El nombre no puede superar 255 caracteres.',
            'scheduled_date.date'        => 'La fecha programada no tiene un formato valido.',
            'executed_date.date'         => 'La fecha de ejecucion no tiene un formato valido.',
            'compliance_status.required' => 'El estado de cumplimiento es obligatorio.',
            'compliance_status.in'       => 'El estado de cumplimiento debe ser: pending, in_progress, completed o not_completed.',
        ];
    }
}
