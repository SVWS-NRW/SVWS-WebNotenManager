<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

/**
 * Defining the CheckIfOtpPending middleware
 */
class CheckIfOtpPending
{
    /**
     * Handle an incoming request.
     * This middleware checks if the verified user has an otp check pending.
     * If otp is pending, the request is allowed to proceed. Otherwise, the user is redirected.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        // Check if the authenticated user has an otp procedure pending
        $user = auth()->user();
        $otpPending = Cache::get('otp_' . $user->id);

        // If the user has an otp pending, continue processing the request
        if ($otpPending) {
            return $next($request);
        }

        // If the user does not have any otp procedure pending, redirect them to the Leistungsdatenuebersicht route
        return redirect(route('leistungsdatenuebersicht'));
    }
}
