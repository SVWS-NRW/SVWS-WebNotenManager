<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GreaterThanOrEqualWhenPresent implements Rule
{
    public function __construct(private int|null $right = null) {}

    public function passes($attribute, mixed $value): bool
    {
		if (!is_int((int) $value)) {
			return false;
		}

		if ($value < 0) {
			return false;
		}

		if (in_array($value, [null, ''])) {
			return in_array($this->right, [null, '']);
		}

		return $value >= ($this->right ?? 0);
    }

    public function message(): string
    {
        return 'The validation error message.';
    }
}
