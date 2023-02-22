<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    public function index(string $type): JsonResponse
	{
		abort_unless(boolean: auth()->check() && auth()->user()->isAdministrator(), code: Response::HTTP_FORBIDDEN);

		return response()->json(
			data: SettingResource::collection(
				resource: Setting::where(column: 'type', operator: '=', value: $type)->get()
			)
		);
	}

    public function update(): JsonResponse
	{
		abort_unless(boolean: auth()->check() && auth()->user()->isAdministrator(), code: Response::HTTP_FORBIDDEN);

		collect(value: request()->settings)->each(callback: fn (string $value, string $key): bool =>
			Setting::query()
				->where(column: 'key', operator: '=', value: $key)
				->where(column: 'type', operator: '=', value: request()->type)
				->firstOrFail()
				->update(attributes: ['value' => $value])
		);

		return response()->json(status: Response::HTTP_NO_CONTENT);
	}
}
