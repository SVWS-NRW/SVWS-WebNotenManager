<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class TwoFactorOtp
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if OTP is required for the route
        $user = auth()->user();

        if ($user?->mustVerifyOtp() && Cache::get('otp_' . $user->id)) {
            return redirect()->route('otp');
        }

        return $next($request);
    }
}
