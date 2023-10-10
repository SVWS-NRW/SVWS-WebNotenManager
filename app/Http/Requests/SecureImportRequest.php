<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SecureImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required', 'file'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'Datei',
        ];
    }
}
