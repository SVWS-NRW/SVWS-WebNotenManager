<?php

namespace App\Http\Requests\Fehlstunden;

use Illuminate\Foundation\Http\FormRequest;

class LeistungGesamtRequest extends FormRequest
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
			'value' => ['required', 'integer', 'min:0', 'gte:' . $this->leistung->fehlstundenUnentschuldigt],
        ];
    }

	public function attributes(): array
	{
		return [
			'value' => 'Fehlstunden gesamt',
		];
	}
}
