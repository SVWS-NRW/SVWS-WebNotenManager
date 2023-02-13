<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingController extends Controller
{
    public function get(string $type): Collection
	{
		return Setting::query()
			->where(column: 'type', operator: '=', value: $type)
			->get()
			->pluck(value: 'value', key: 'key');
	}

    public function set(): void
	{
		collect(value: request()->settings)->each(callback: fn (string $value, string $key): bool =>
			Setting::query()
				->where(column: 'key', operator: '=', value: $key)
				->where(column: 'type', operator: '=', value: request()->type)
				->firstOrFail()
				->update(attributes: ['value' => $value])
		);
	}
}
