<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Settings\FilterSettings;
use App\Settings\GeneralSettings;
use App\Settings\MatrixSettings;
use App\Settings\SicherheitSettings;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    public function index(string $group): JsonResponse 
	{
		return response()->json($this->getSetting($group));
	}

    public function update(string $group): JsonResponse
	{
		$setting = $this->getSetting($group);
		$setting->{request()->column} = request()->value;
		$setting->save();

		return response()->json(status: Response::HTTP_NO_CONTENT);
	}

    public function bulkUpdate(string $group): JsonResponse
	{
		$setting = $this->getSetting($group);

		collect(request()->settings)->each(
			fn ($value, $key): string => $setting->$key = $value
		);

		$setting->save();

		return response()->json(status: Response::HTTP_NO_CONTENT);
	}

	private function getSetting(
		string $group
	): FilterSettings | MatrixSettings | SicherheitSettings | GeneralSettings {
		return app(match ($group) {
			'filter' => FilterSettings::class,
			'matrix' => MatrixSettings::class,
//TODO:check if this makes sense here
			'sicherheit' => SicherheitSettings::class,
			default => GeneralSettings::class,
		});
	}
}
