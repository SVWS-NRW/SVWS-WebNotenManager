<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RedirectIfKlassenleitung
{
    public function handle(Request $request, Closure $next): RedirectResponse|JsonResponse|Response
    {
		if (auth()->user()->klassen->count() > 0 || auth()->user()->isAdministrator()) {
        	return $next($request);
		}

		return redirect()->route(route: 'mein_unterricht');
    }
}
