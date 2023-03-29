<?php

namespace App\Http\Requests;

use App\Models\Bemerkung;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SchuelerBemerkungenRequest extends FormRequest
{
    public function authorize(): bool
    {
		if (auth()->user()->is_administrator) {
			return true;
		}

		return in_array(
			needle: $this->schueler->klasse_id,
			haystack: auth()->user()->klassen()->pluck(column: 'id')->toArray()
		);
    }

    public function rules(): array
    {
        return [
            'key' => Rule::in(values: Bemerkung::ALLOWED_BEMERKUNGEN),
			'value' => [
				'nullable', 'string'
			],
        ];
    }
}
