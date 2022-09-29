<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingController extends Controller
{
    public function get(string $type): Collection
	{
		return Setting::where(['type' => $type])->get()->pluck('value', 'key');
	}

    public function set(): void
	{
		collect(request()->settings)->each(fn (string $value, string $key) =>
			Setting::where(['key' => $key, 'type' => request()->type])
				->firstOrFail()
				->update(['value' => $value])
		);
	}
}
