<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class Check2fa
{
     protected function redirectTo($request)
     {
         if (! $request->expectsJson()) {
             return route('2fa');
         }
     }
}
