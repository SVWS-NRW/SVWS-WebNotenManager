<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\FilterValidationRequest;
use App\Http\Requests\Settings\MailSendCredentialsRequest;
use App\Services\EnvService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class EnvController extends Controller
{
    public function getMailSendCredentials(): JsonResponse
    {
        return response()->json(config('wenom.mail_send_credentials'));
    }

    public function updateMailSendCredentials(MailSendCredentialsRequest $request, EnvService $service): JsonResponse
    {
        try {
            $service->bulkUpdate($request->validated());
        } catch (FileNotFoundException $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(status: Response::HTTP_NO_CONTENT);
     }

    public function getFilters(): JsonResponse
    {
        return response()->json(config('wenom.filters'));
    }

    public function setFilters(FilterValidationRequest $request, EnvService $service): JsonResponse
    {
        collect($request->all())->each(fn (array $item, string $key) =>
            collect($item)->each(fn ($value, string $itemKey) =>
                $service->update(
                    sprintf('%s_%s', strtoupper($key), strtoupper($itemKey)), $value
                )
            )
        );

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
