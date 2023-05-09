<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\GreaterThanOrEqualWhenPresent;
use Illuminate\Foundation\Http\FormRequest;

class GfsRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
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
