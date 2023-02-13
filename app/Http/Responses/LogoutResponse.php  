<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Symfony\Component\HttpFoundation\Response as Status;

class LogoutResponse implements LogoutResponseContract
{
	public function toResponse($request)
	{
		return $request->wantsJson()
			? new JsonResponse(data: '', status: Status::HTTP_NO_CONTENT)
			: redirect(to: '/login');
	}
}