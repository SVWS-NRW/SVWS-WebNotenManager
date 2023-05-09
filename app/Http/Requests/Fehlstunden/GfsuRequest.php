<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\LessThanOrEqualWhenPresent;
use Illuminate\Foundation\Http\FormRequest;

class GfsuRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
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
