<?php

namespace App\Providers;

use App\Models\Bemerkung;
use App\Models\Leistung;
use App\Observers\BemerkungObserver;
use App\Observers\LeistungObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];
    
    public function boot(): void
    {
		//
	}

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
