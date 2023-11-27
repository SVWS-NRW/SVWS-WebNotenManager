<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;

use App\Http\Requests\Settings\MailSendCredentialsRequest;
use App\Services\EnvService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;

use Symfony\Component\HttpFoundation\Response;

class EnvController extends Controller
{
    public function getMailSendCredentials(): JsonResponse
    {
        return response()->json(config('wenom.mail_send_credentials'), Response::HTTP_OK);

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
}
