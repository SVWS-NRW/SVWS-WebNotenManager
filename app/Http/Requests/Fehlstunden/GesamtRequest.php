<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\GreaterThanOrEqualWhenPresent;
use Illuminate\Foundation\Http\FormRequest;

class GesamtRequest extends FormRequest
{
	public function authorize(): bool
	{
		if (auth()->guest()) {
			return false;
		}

		if (auth()->user()->isAdministrator()) {
			return true;
		}

		return in_array(
			needle: $this->schueler->klasse_id,
			haystack: auth()->user()->klassen->pluck(value: 'id')->toArray()
		);
	}

	public function rules(): array
	{
		return [
			'value' => new GreaterThanOrEqualWhenPresent(
				right: $this->schueler->lernabschnitt->fehlstundenGesamtUnentschuldigt,
			),
		];
	}

	public function attributes(): array
	{
		return [
			'value' => 'Fehlstunden gesamt',
		];
	}
}
