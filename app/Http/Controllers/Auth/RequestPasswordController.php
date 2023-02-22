<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirstLoginRequest;
use App\Models\User;
use App\Notifications\RequestPasswordNotification;
use Illuminate\Auth\Passwords\PasswordBroker;

class RequestPasswordController extends Controller
{
	public function store(FirstLoginRequest $request): void // TODO: Has to be reworked.
	{
		try {
			$user = User::query()
				->where(column: $request->only(keys: ['email', 'kuerzel']))
				->whereHas(
					relation: 'daten',
					callback: fn ($daten) => $daten->where(
						column: 'schulnummer', operator: '=', value: (int) $request->schulnummer
					)
				)
				->firstOrFail();

			$token = app(abstract: PasswordBroker::class)->createToken(user: $user);
			$user->notify(instance: new RequestPasswordNotification(token: $token));
		} finally {
			return;
		}
	}
}
