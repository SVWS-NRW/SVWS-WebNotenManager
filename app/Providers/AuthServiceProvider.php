<?php

namespace App\Providers;

use App\Models\Leistung;
use App\Models\Schueler;
use App\Policies\LeistungsFehlstundenPolicy;
use App\Policies\SchuelerFehlstundenPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
         Leistung::class => LeistungsFehlstundenPolicy::class,
         Schueler::class => SchuelerFehlstundenPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
