<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirstLoginRequest;
use App\Models\User;
use App\Notifications\RequestPasswordNotification;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Database\Eloquent\Builder;
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
			$user = User::query()
				->where($request->only('email', 'kuerzel'))
				->whereHas('daten', fn ($daten) => $daten->where(['schulnummer' => (int) $request->schulnummer]))
				->firstOrFail();

			$token = app(PasswordBroker::class)->createToken($user);
			$user->notify(new RequestPasswordNotification($token));
		} finally {
			return;
		}
	}
}
