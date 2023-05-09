<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\GreaterThanOrEqualWhenPresent;
use App\Settings\MatrixSettings;
use Illuminate\Foundation\Http\FormRequest;

class FsRequest extends FormRequest
{
	public function authorize(MatrixSettings $settings): bool
    {
		return true;
    }

    public function rules(): array
    {
        return [
			'value' => new GreaterThanOrEqualWhenPresent(
				right: $this->leistung->fehlstundenUnentschuldigtFach,
			),
        ];
    }

	public function attributes(): array
	{
		return [
			'value' => 'Fehlstunden Fach',
		];
	}
}
