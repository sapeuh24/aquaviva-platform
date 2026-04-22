<?php

namespace App\Http\Requests\Evidences;

use Illuminate\Foundation\Http\FormRequest;

class UploadEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Evidence::class);
    }

    public function rules(): array
    {
        $maxKb = config('evidences.max_file_size_kb', 51200);

        return [
            'activity_id' => ['required', 'integer', 'exists:activities,id'],
            'file'        => ['required', 'file', "max:{$maxKb}"],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
