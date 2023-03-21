<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MeinUnterricht
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
		$hasLerngruppen = auth()->user()->lerngruppen()->count();

		if ($hasLerngruppen) {
			return $next($request);
		}

		return redirect(
			to: route(name: 'leistungsdatenuebersicht')
		);
    }
}
