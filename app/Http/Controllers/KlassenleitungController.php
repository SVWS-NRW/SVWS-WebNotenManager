<?php

namespace App\Http\Controllers;

use App\Models\Schueler;
use Inertia\Inertia;
use Inertia\Response;

class KlassenleitungController extends Controller
{
    public function __invoke(): Response
    {
		return Inertia::render('Klassenleitung', ['schueler' => Schueler::all()]);
    }
}