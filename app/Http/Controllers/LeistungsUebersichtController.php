<?php

namespace App\Http\Controllers;

use App\Models\Schueler;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LeistungsUebersichtController extends Controller
{
	public function __invoke(): Response
	{
		return Inertia::render('Leistungsdatenuebersicht', ['schueler' => Schueler::all()]);
	}
}
