<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\GreaterThanOrEqualWhenPresent;
use Illuminate\Foundation\Http\FormRequest;

class FachRequest extends FormRequest
{
    public function authorize(): bool
    {
		if (auth()->guest()) {
			return false;
		}

		if (auth()->user()->isAdministrator()) {
			return false;
		}

		return in_array(
			needle: $this->leistung->lerngruppe_id,
			haystack: auth()->user()->lerngruppen->pluck(value: 'id')->toArray()
		);
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
