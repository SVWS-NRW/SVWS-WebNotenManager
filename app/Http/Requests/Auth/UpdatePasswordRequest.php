<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * A FormRequest class to handle validation and authorization for requests related current resource.
 */
class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the given ability should be granted for the current user.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->guest();
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => [
				'required',
			],
            'email' => [
				'required', 'email:rfc,dns'
			],
            'password' => [
				'required', 'min:8', 'confirmed',
			],
        ];
    }

    /**
     * Validation attributes
     *
     * @return array
     */
	public function attributes(): array
	{
		return [
            'token' => 'Token',
			'email' => 'E-Mail-Adresse',
			'password' => 'Passwort',
		];
	}
}
