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
			'schoolName' => config(key:'app.school_name'),
			'settings' => [
				'general' => app(abstract: GeneralSettings::class),
				'filters' => app(abstract: FilterSettings::class),
				'matrix' => app(abstract: MatrixSettings::class),
				'gdpr' => app(abstract: GdprSettings::class),
			],
			'version' => config(key: 'wenom.version'),
        ]);
    }
}
