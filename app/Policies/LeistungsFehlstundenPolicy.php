<?php

namespace App\Policies;

use App\Models\Leistung;
use App\Models\User;
use App\Settings\MatrixSettings;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeistungsFehlstundenPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Leistung $leistung, MatrixSettings $settings): bool
    {
		if ($user->isAdministrator()) {
			return true;
		}

		if (!$leistung->schueler->klasse->editable_fehlstunden) {
			return false;
		}

		if ($leistung->sharesKlasseWithCurrentUser() && $settings->lehrer_can_override_fachlehrer) {
			return true;
		}

		return $leistung->sharesLerngruppeWithCurrentUser();
    }
}