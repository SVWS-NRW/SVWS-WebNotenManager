<?php

namespace App\Http\Requests\Fehlstunden;

use App\Rules\LessThanOrEqualWhenPresent;
use App\Settings\MatrixSettings;
use Illuminate\Foundation\Http\FormRequest;

class FsuRequest extends FormRequest
{
	public function authorize(MatrixSettings $settings): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'value' => new LessThanOrEqualWhenPresent(
				$this->leistung->fehlstundenFach,
			),
		];
	}

	public function attributes(): array
	{
		return [
			'value' => 'Fehlstunden unentschuldigt Fach',
		];
	}
}
