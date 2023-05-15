<?php

namespace App\Policies;

use App\Models\Schueler;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchuelerFehlstundenPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Schueler $schueler): bool
	{
		if ($user->isAdministrator()) {
			return true;
		}

		if ($schueler->klasse->editable_fehlstunden) {
			return false;
		}


		if (!$schueler->klasse->editable_fehlstunden) {
			return false;
		}

		if (!$schueler->klasse->toggleable_fehlstunden) {
			return false;
		}

		return $schueler->sharesKlasseWithCurrentUser();
    }
}
