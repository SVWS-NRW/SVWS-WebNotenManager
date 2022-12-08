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
		if (auth()->user() instanceof User) {
			return false;
		}

		if (in_array($this->schueler->klasse_id, auth()->user()->klassen()->pluck('id')->toArray())) {
			return true;
		}

        return false;
    }

    public function rules(): array
    {
        return [
            'key' => Rule::in(Bemerkung::ALLOWED_BEMERKUNGEN),
			'value' => ['nullable', 'string'],
        ];
    }
}
