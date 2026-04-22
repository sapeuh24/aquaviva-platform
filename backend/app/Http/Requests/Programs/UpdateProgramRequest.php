<?php

namespace App\Http\Requests\Programs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $programId = $this->route('program')?->id;
        $companyId = $this->input('company_id', $this->route('program')?->company_id);

        return [
            'company_id'              => ['required', 'integer', 'exists:companies,id'],
            'environmental_medium_id' => ['required', 'integer', 'exists:environmental_media,id'],
            'name'                    => ['required', 'string', 'max:200'],
            'code'                    => ['required', 'string', 'max:50', "unique:programs,code,{$programId},id,company_id,{$companyId}"],
            'description'             => ['nullable', 'string'],
            'is_active'               => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required'              => 'La empresa es obligatoria.',
            'company_id.exists'                => 'La empresa seleccionada no existe.',
            'environmental_medium_id.required' => 'El medio ambiental es obligatorio.',
            'environmental_medium_id.exists'   => 'El medio ambiental seleccionado no existe.',
            'name.required'                    => 'El nombre del programa es obligatorio.',
            'name.max'                         => 'El nombre no puede superar 200 caracteres.',
            'code.required'                    => 'El codigo del programa es obligatorio.',
            'code.max'                         => 'El codigo no puede superar 50 caracteres.',
            'code.unique'                      => 'Ya existe un programa con este codigo para la empresa.',
        ];
    }
}
