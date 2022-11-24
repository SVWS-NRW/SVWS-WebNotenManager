<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth.user' => fn () => $request->user() ? $request->user()->only('id', 'vorname', 'nachname', 'email', 'klassen') : null,
			'auth.administrator' => auth()->guard('admin')->check(),
			'schoolName' => config('app.school_name'),
			'settings' => Setting::all()->pluck('value','key'),
			'note_entry_disabled' => Setting::entryDisabled('note_entry_until'),
			'warning_entry_disabled' => Setting::entryDisabled('warning_entry_until'),
        ]);
    }
}
