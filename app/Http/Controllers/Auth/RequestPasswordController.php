<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirstLoginRequest;
use App\Models\Lehrer;

use App\Notifications\RequestPasswordNotification;
use Illuminate\Auth\Passwords\PasswordBroker;

use Inertia\Inertia;
use Inertia\Response;

class RequestPasswordController extends Controller
{
	public function store(FirstLoginRequest $request): void // TODO
	{
		try {
			$lehrer = Lehrer::query()
				->where(column: $request->only(keys: ['email', 'kuerzel']))
				->whereHas(
					relation: 'daten',
					callback: fn ($daten) => $daten->where(
						column: 'schulnummer', operator: '=', value: (int) $request->schulnummer
					)
				)
				->firstOrFail();

			$token = app(abstract: PasswordBroker::class)->createToken(user: $lehrer);
			$lehrer->notify(instance: new RequestPasswordNotification(token: $token));
		} finally {
			return;
		}
	}
}
