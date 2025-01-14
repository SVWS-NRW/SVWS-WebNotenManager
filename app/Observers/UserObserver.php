<?php

namespace App\Observers;

use App\Models\User;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Str;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * @param User $user
     */
    public function creating(User $user): void
    {
        // Map the id, since this field cannot be auto incremented.
        $user->ext_id = $user->id;

        // Perform validations with fallback values.
        $user->email = $this->validateEmail($user, $this->safeEmail(32));
        $user->email_valid = $this->validEmail($user);
        $user->geschlecht = $this->validateGender($user, User::FALLBACK_GENDER);

        // Set password while creating. In devmode password is "password".
        $user->password = app()->environment('production') ? Str::random(32) : Hash::make('password');

        // Ignore unrelated columns
        unset($user->eMailDienstlich, $user->id, $user->passwordHash, $user->tsPasswordHash);

        if ($user->email_valid) {
            // Show success message
            session()->push('import-success', [
                'message' => 'Die Lehrer Ressource wurde erfolgreich angelegt.',
                'data' => [
                    'ext_id' => $user->ext_id,
                ],
            ]);
        } else {
            // Show success message
            session()->push('import-error', [
                'message' => 'Lehrer: Importfehler',
                'data' => [
                    'ext_id' => $user->ext_id,
                    'email' => $user->email,
                ],
            ]);
        }
    }

    /**
     * Handle the User "updating" event.
     *
     * @param User $user
     * @return void
     */
    public function updating(User $user): void
    {
        // Perform validations with fallback values.
        $user->email = $this->validateEmail($user, $user->getOriginal('email'));
        $user->email_valid = $this->validEmail($user);
        $user->geschlecht = $this->validateGender($user, $user->getOriginal('geschlecht'));

        // Ignore unrelated columns
        unset($user->eMailDienstlich, $user->id, $user->passwordHash, $user->tsPasswordHash);

        if ($user->email_valid) {
            // Show success message
            session()->push('import-success', [
                'message' => 'Die Lehrer Ressource wurde erfolgreich aktualisiert.',
                'data' => [
                    'ext_id' => $user->ext_id,
                    'email' => $user->email,
                ],
            ]);
        } else {
            // Show success message
            session()->push('import-error', [
                'message' => 'Lehrer: Importfehler',
                'data' => [
                    'ext_id' => $user->ext_id,
                    'email' => $user->email,
                ],
            ]);
        }
    }

    /**
     * Generate random safe email address
     *
     * @param int $length
     * @return string
     */
    private function safeEmail(int $length = 16): string
    {
        return sprintf('%s@%s', Str::random($length), Str::random($length));
    }

    /**
     * Check whether the email is valid
     *
     * @param User $user
     * @param string|null $fallback
     * @return bool
     */
    private function validEmail(User $user, string|null $fallback = null): bool
    {
        $validator = Validator::make(
            ['email' => $user->email],
            ['email' => ['required', 'email:rfc,dns']]
        );

        if ($validator->valid()) {
            return true;
        }

        if ($fallback) {
            $this->invalidEmailErrorMessage($user, $fallback, $validator->errors()->all());
        }

        return false;
    }

    /**
     * Format email
     *
     * If none provided, generate a random one.
     * If duplicate, generate a random one.
     * If invalid, generate a random one.
     *
     * @param User $user
     * @param string $fallback
     * @return string
     */
    private function validateEmail(User $user, string $fallback): string
    {
        // Check if user with given email address and different id already exists. #393
        $existingUser = User::query()
            ->where('ext_id', '!=', $user->id)
            ->where(['email' => $user->email]);

        if ($existingUser->exists()) {
            $this->userExistsErrorMessage($user, $existingUser, $fallback);
            return $fallback;
        }

        if ($this->validEmail($user)) {
            return strtolower($user->email);
        }

        return $fallback;
    }

    /**
     * Validate the gender
     *
     * @param User $user
     * @param string $fallback
     * @return string
     */
    private function validateGender(User $user, string $fallback): string
    {
        if (in_array($user->geschlecht, User::GENDERS)) {
            return $user->geschlecht;
        }

        $this->invalidGenderErrorMessage($user, $fallback);
        return $fallback;
    }

    /**
     * Provide error message when user's gender is incorrect.
     *
     * @param User $user
     * @param User $existingUser
     * @param array $errors
     */
    private function invalidGenderErrorMessage(User $user, string $fallback): void
    {
        $message = sprintf('Die Lehrer Geschlecht ist nicht gueltig. Wird automatisch auf "%s" gesetzt.', $fallback);

        session()->push('import-error', [
            'message' => $message,
            'data' => [
                'id' => $user->ext_id,
                'geschlecht' => $user->geschlecht,
            ],
        ]);
    }

    /**
     * Provide error message when a user with given email but different id already exists
     *
     * @param User $user
     * @param User $existingUser
     * @param array $errors
     */
    private function invalidEmailErrorMessage(User $user, string $fallback, array $errors): void
    {
        $message = sprintf('Die Lehrer E-Mail-Adresse ist nicht gueltig. Wird automatisch auf "%s" gesetzt.', $fallback);

        session()->push('import-error', [
            'message' => $message,
            'data' => [
                'id' => $user->ext_id,
                'email' => $user->email,
            ],
            'errors' => $errors,
        ]);
    }

    /**
     * Provide error message when a user with given email but different id already exists
     *
     * @param User $user
     * @param Builder $existingUser
     */
    private function userExistsErrorMessage(User $user, Builder $existingUser, string $fallback): void
    {
        $message = sprintf(
            'Die Lehrer E-Mail-Adresse ist bereits vergeben. Wird automatisch auf "%s" gesetzt.',
            $fallback,
        );

        session()->push('import-error', [
            'message' => $message,
            'data' => [
                'email' => $user->email,
                'current_id' => $user->ext_id,
                'existing_id' => $existingUser->first()->ext_id,
            ],
        ]);
    }
}
