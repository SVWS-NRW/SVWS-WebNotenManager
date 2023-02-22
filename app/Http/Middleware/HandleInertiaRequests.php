<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version(request: $request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth.user' => fn (): array|null => $request->user()
				? $request->user()->only('id', 'vorname', 'nachname', 'email', 'klassen')
				: null,
			'auth.administrator' => auth()->check() && auth()->user()->isAdministrator(),
			'schoolName' => config(key:'app.school_name'),
			'settings' => Setting::all()->pluck(value: 'value', key: 'key'),
			'note_entry_disabled' => Setting::entryDisabled(entry: 'note_entry_until'),
			'warning_entry_disabled' => Setting::entryDisabled(entry: 'warning_entry_until'),
			'version' => config(key: 'wenom.version'),
        ]);
    }
}
