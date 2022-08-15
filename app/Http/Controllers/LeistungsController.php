<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeistungCollection;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Leistung;
use App\Models\Note;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class LeistungsController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard');
    }
}
