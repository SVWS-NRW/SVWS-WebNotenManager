<?php

namespace App\Http\Controllers;

use App\Models\Lehrer;
use App\Services\DataImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ImportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): void
    {
		$service = new DataImportService(
			lehrer: request()->lehrer,
			foerderschwerpunkte: request()->foerderschwerpunkte,
			klassen: request()->klassen,
			noten: request()->noten,
			jahrgaenge: request()->jahrgaenge,
			faecher: request()->faecher,
			floskelgruppen: request()->floskelgruppen,
			lerngruppen: request()->lerngruppen,
			teilleistungsarten: request()->teilleistungsarten,
			schueler: request()->schueler,
		);

		$service->import();

		Lehrer::all()->each(fn (Lehrer $lehrer) => $lehrer->update(['password' => Hash::make('password')]));
    }
}
