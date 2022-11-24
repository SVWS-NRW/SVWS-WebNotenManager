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
    public function index(): Response
	{
		return Inertia::render('Auth/RequestPassword');
	}

	public function store(FirstLoginRequest $request): void
	{
		try {
			$lehrer = Lehrer::query()
				->where($request->only('email', 'kuerzel'))
				->whereHas('daten', fn ($daten) => $daten->where(['schulnummer' => (int) $request->schulnummer]))
				->firstOrFail();

			$token = app(PasswordBroker::class)->createToken($lehrer);
			$lehrer->notify(new RequestPasswordNotification($token));
		} finally {
			return;
		}
	}
}
