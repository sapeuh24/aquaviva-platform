<?php

namespace App\Http\Requests\Indicators;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIndicatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'worksheet_id'           => ['required', 'integer', 'exists:worksheets,id'],
            'indicator_frequency_id' => ['required', 'integer', 'exists:indicator_frequencies,id'],
            'name'                   => ['required', 'string', 'max:255'],
            'objective'              => ['nullable', 'string'],
            'target'                 => ['nullable', 'string'],
            'next_due_date'          => ['nullable', 'date'],
            'is_active'              => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'worksheet_id.required'           => 'La ficha de trabajo es obligatoria.',
            'worksheet_id.exists'             => 'La ficha de trabajo seleccionada no existe.',
            'indicator_frequency_id.required' => 'La frecuencia del indicador es obligatoria.',
            'indicator_frequency_id.exists'   => 'La frecuencia seleccionada no existe.',
            'name.required'                   => 'El nombre del indicador es obligatorio.',
            'name.max'                        => 'El nombre no puede superar 255 caracteres.',
            'next_due_date.date'              => 'La fecha de proximo vencimiento no tiene un formato valido.',
        ];
    }
}
