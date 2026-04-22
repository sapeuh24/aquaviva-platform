<?php

namespace App\Http\Requests\Evidences;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StoreEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file'        => [
                'required',
                'file',
                'max:51200',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! ($value instanceof UploadedFile)) {
                        $fail('El archivo no es valido.');
                        return;
                    }

                    $allowedMimes = [
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        'video/mp4',
                        'video/quicktime',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ];

                    $realMime = $value->getMimeType();

                    if (! in_array($realMime, $allowedMimes, strict: true)) {
                        $fail("El tipo de archivo '{$realMime}' no esta permitido.");
                    }
                },
            ],
            'activity_id' => ['required', 'integer', 'exists:activities,id'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required'      => 'El archivo es obligatorio.',
            'file.file'          => 'El campo debe ser un archivo.',
            'file.max'           => 'El archivo no puede superar 50 MB.',
            'activity_id.required' => 'La actividad es obligatoria.',
            'activity_id.exists'   => 'La actividad seleccionada no existe.',
            'description.max'      => 'La descripcion no puede superar 500 caracteres.',
        ];
    }
}
