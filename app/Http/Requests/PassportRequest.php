<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->is_administrator;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'unique:oauth_clients,name',
            ],
        ];
    }
}
