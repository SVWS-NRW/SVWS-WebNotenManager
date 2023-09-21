<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\FilterValidationRequest;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserSettingsController extends Controller
{
    private array $filterColumns = [
        'filters_leistungsdatenuebersicht',
        'filters_meinunterricht',
    ];

    public function getAllFilters(): JsonResponse
    {
        $data = [
            'filters_leistungsdatenuebersicht' => config('wenom.filters.leistungsdatenuebersicht'),
            'filters_meinunterricht' => config('wenom.filters.mein_unterricht'),
        ];

        return response()->json(
            $this->retrieveFilters($data, $this->filterColumns)
        );
    }

    public function getFilters(string $group = 'leistungsdatenuebersicht'): JsonResponse
    {
        abort_unless(in_array($group, ['leistungsdatenuebersicht', 'meinunterricht']), 404);

        return response()->json(
            $this->retrieveFilters(
                config("wenom.filters.{$group}"),
                ["filters_{$group}"],
            )
        );
    }

    public function setFilters(FilterValidationRequest $request): JsonResponse
    {
        auth()->user()->settings()->update(
            $request->safe($this->filterColumns)
        );

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    private function retrieveFilters(array $data, array $columns): array
    {
        return UserSetting::query()
            ->firstOrCreate(['user_id' => auth()->id()], $data)
            ->only($columns);
    }


}
