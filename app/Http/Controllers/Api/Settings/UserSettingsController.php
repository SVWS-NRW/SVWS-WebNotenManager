<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\FilterValidationRequest;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\ItemNotFoundException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defining the UserSettings controller
 */
class UserSettingsController extends Controller
{
    /**
     * Define the columns to be filtered
     *
     * @var array|string[]
     */
    private array $filterColumns = [
        'filters_leistungsdatenuebersicht',
        'filters_meinunterricht',
    ];

    /**
     * Retrieves all available filter settings for the authenticated user.
     *
     * @return JsonResponse
     */
    public function getAllFilters(): JsonResponse
    {
        // Fetching and returning the filters for Meinunterricht, Leistungsdatenuebersicht for the authenticated user.
        return response()->json([
            'filters_meinunterricht' => auth()->user()->filters('meinunterricht'),
            'filters_leistungsdatenuebersicht' => auth()->user()->filters('leistungsdatenuebersicht'),
        ]);
    }

    /**
     * Retrieves filter settings for the authenticated user based on a specified group.
     *
     * @param string $group
     * @return JsonResponse
     */
    public function getFilters(string $group = 'leistungsdatenuebersicht'): JsonResponse
    {
        // Abort with a 404 response if the specified group is not valid.
        abort_unless(in_array($group, ['leistungsdatenuebersicht', 'meinunterricht']), 404);

        // Fetching and returning the filters for the specified group for the authenticated user.
        return response()->json(auth()->user()->filters($group));
    }

    /**
     * Updates or creates filter settings for the authenticated user.
     *
     * @param FilterValidationRequest $request
     * @return JsonResponse
     */
    public function setFilters(FilterValidationRequest $request): JsonResponse
    {
        // Updating or creating the user settings for the authenticated user with the specified filter values.
        UserSetting::updateOrCreate(['user_id' => auth()->id()], $request->safe($this->filterColumns));

        // Returning a JSON response with a 204 No Content status, indicating successful processing.
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Get personal setting only
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSettingTwoFactor(Request $request): bool | JsonResponse
    {
        $personalOtpSetting = auth()->user()?->userSettings()->select('twofactor_otp')->first();

        return response()->json($personalOtpSetting);
    }

    /**
     * Updates or creates settings for the authenticated user.
     *
     * @param FilterValidationRequest $request
     * @return JsonResponse
     */
    public function setSettingTwoFactor(Request $request): JsonResponse
    {
        // Updating or creating the user settings for the authenticated user with the specified filter values.
        UserSetting::updateOrCreate(['user_id' => auth()->id()], $request->all());

        // Returning a JSON response with a 204 No Content status, indicating successful processing.
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
