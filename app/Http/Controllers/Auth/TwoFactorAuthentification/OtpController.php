<?php

namespace App\Http\Controllers\Auth\TwoFactorAuthentification;

use App\Services\OtpService;
use App\Http\Controllers\Controller;
use Illuminate\Http\{RedirectResponse, Request};
use Symfony\Component\HttpFoundation\Response;

class OtpController extends Controller
{
    /**
     * Verify OTP
     *
     * @param OtpService $service
     * @param Request $request
     * @return  Inertia
     */
    public function verify(OtpService $service, Request $request): RedirectResponse
    {
        $user = auth()->user();
        $verified = $service->verifyOtp($user, $request->code);

        if (!$verified) {
            return back()->withErrors(['twoFACode' => 'OTP inkorrekt']);
        }

        //Otp is not needed anymore
        $service->forgetOtp($user);

        return redirect()->route('leistungsdatenuebersicht');
    }
}
