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
            'auth.user' => fn () => $request->user() ? $request->user()->only('id', 'vorname', 'nachname', 'email', 'administrator') : null,
			'schoolName' => config('app.school_name'),
			'settings' => Setting::all()->pluck('value','key'),
        ]);
    }
}
