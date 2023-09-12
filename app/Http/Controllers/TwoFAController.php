<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\AuthCode;

class TwoFAController extends Controller
{
    public function index(): response
    {
        return view('2fa');
    }

    public function store(Request $request): response
    {
        $request->validate([
            'fa_auth_code' => 'required',
        ]);

        $find = AuthCode::where('user_id', auth()->user()->id)
            ->where('2fa_auth_code', $request->fa_auth_code)
            ->where('updated_at', '>=', now()->subMinutes(2))
            ->first();
        if (!is_null($find)) {
            Session::put('user_2fa', auth()->user()->id);
            return redirect()->route('login');
        }
        return back()->with('error', 'Der eingegebene Code ist falsch');
    }

    public function resend(): response
    {
        auth()->user()->generateCode();
        return back()->with('success', 'Der Code wird per Mail versendet');
    }
}
