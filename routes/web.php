<?php

use App\Http\Controllers\LeistungsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(fn () => Route::get('/', LeistungsController::class)->name('dashboard'));




