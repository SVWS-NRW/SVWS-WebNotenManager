<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\FilterValidationRequest;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserSettingsController extends Controller
{
    private array $filterColumns = [
        'filters_leistungsdatenuebersicht',
        'filters_meinunterricht',
    ];

    public function getAllFilters(): JsonResponse
    {
        return response()->json([
            'filters_meinunterricht' => auth()->user()->filters('meinunterricht'),
            'filters_leistungsdatenuebersicht' => auth()->user()->filters('leistungsdatenuebersicht'),
        ]);
    }

    public function getFilters(string $group = 'leistungsdatenuebersicht'): JsonResponse
    {
        abort_unless(in_array($group, ['leistungsdatenuebersicht', 'meinunterricht']), 404);

        return response()->json(auth()->user()->filters($group));
    }

    public function setFilters(FilterValidationRequest $request): JsonResponse
    {
        UserSetting::updateOrCreate(['user_id' => auth()->id()], $request->safe($this->filterColumns));

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}