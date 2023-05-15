<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Settings\FilterSettings;
use App\Settings\GeneralSettings;
use App\Settings\MatrixSettings;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    public function index(string $group): JsonResponse // TODO: Test
	{
		abort_unless(
			boolean: auth()->check() && auth()->user()->isAdministrator(),
			code: Response::HTTP_FORBIDDEN,
		);

		return response()->json(
			data: $this->getSetting(group: $group)
		);
	}

    public function update(string $group): JsonResponse
	{

		abort_unless(
			boolean: auth()->check() && auth()->user()->isAdministrator(),
			code: Response::HTTP_FORBIDDEN,
		);

		$setting = $this->getSetting(group: $group);
		$setting->{request()->column} = request()->value;
		$setting->save();

		return response()->json(status: Response::HTTP_NO_CONTENT);
	}

    public function bulkUpdate(string $group): JsonResponse
	{
		abort_unless(
			boolean: auth()->check() && auth()->user()->isAdministrator(),
			code: Response::HTTP_FORBIDDEN,
		);

		$setting = $this->getSetting(group: $group);

		collect(value: request()->settings['_value'])
			->each(callback: fn ($value, $key): string => $setting->$key = $value);

		$setting->save();

		return response()->json(status: Response::HTTP_NO_CONTENT);
	}

	private function getSetting(string $group): FilterSettings|MatrixSettings|GeneralSettings
	{
		return app(abstract: match ($group) {
			'filter' => FilterSettings::class,
			'matrix' => MatrixSettings::class,
			default => GeneralSettings::class,
		});
	}
}
