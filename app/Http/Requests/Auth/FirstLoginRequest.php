<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class FirstLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guest();
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:dns,rfc', 'min:5', 'max:255'],
            'kuerzel' => ['required', 'string', 'min:2', 'max:255'],
            'schulnummer' => ['required', 'numeric'],
        ];
    }

	public function attributes(): array
	{
		return [
			'email' => 'E-Mail-Adresse',
			'kuerzel' => 'Lehrkraftkürzel',
			'schulnummer' => 'Schulnummer',
		];
	}
}
