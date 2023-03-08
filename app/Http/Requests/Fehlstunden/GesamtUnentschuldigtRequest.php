<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\LessThanOrEqualWhenPresent;
use Illuminate\Foundation\Http\FormRequest;

class GesamtUnentschuldigtRequest extends FormRequest
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
			needle: $this->schueler->klasse_id,
			haystack: auth()->user()->klassen->pluck(value: 'id')->toArray()
		);
	}

	public function rules(): array
	{
		return [
			'value' => new LessThanOrEqualWhenPresent(
				right: $this->schueler->lernabschnitt->fehlstundenGesamt,
			),
		];
	}

	public function attributes(): array
	{
		return [
			'value' => 'Fehlstunden gesamt unentschuldigt',
		];
	}
}
