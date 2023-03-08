<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GreaterThanOrEqualWhenPresent implements Rule
{
    public function __construct(private int|null $right = null) {}

    public function passes($attribute, mixed $value): bool
    {
		if (!is_int(value: (int) $value)) {
			return false;
		}

		if ($value < 0) {
			return false;
		}

		if (in_array(needle: $value, haystack: [null, ''])) {
			return in_array(needle: $this->right, haystack: [null, '']);
		}

		return $value >= ($this->right ?? 0);
    }

    public function message(): string
    {
        return 'The validation error message.';
    }
}
