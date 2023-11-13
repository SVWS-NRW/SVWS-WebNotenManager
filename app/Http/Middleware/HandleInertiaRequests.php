<?php

namespace App\Http\Middleware;

use App\Settings\FilterSettings;
use App\Settings\GdprSettings;
use App\Settings\GeneralSettings;
use App\Settings\MatrixSettings;
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
				? $request->user()->only('id', 'vorname', 'nachname', 'email', 'klassen', 'lerngruppen')
				: null,
			'auth.administrator' => $request->user() ? $request->user()->is_administrator : false,
			'schoolName' => config('app.school_name'),
			'settings' => [
				'general' => app(GeneralSettings::class),
				'filters' => app(FilterSettings::class),
				'matrix' => app(MatrixSettings::class),
				'gdpr' => app(GdprSettings::class),
			],
			'version' => config('wenom.version'),
            'npm' => config('wenom.npm'),
        ]);
    }
}
