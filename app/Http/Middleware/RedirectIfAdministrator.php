<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectIfAdministrator
{
    public function handle(Request $request, Closure $next): JsonResponse|RedirectResponse
	{
		if (auth()->guard(name: 'administrator')->check()) {
			return $next($request);
		}

		return redirect()->route(route: 'dashboard');
    }
}
