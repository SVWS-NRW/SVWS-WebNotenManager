<?php

namespace App\Http\Controllers;

use App\Models\Lehrer;
use App\Services\DataImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ImportController extends Controller
{
    public function __invoke(Request $request): void
    {
		$keys = [
			'lehrer',
			'foerderschwerpunkte',
			'klassen',
			'noten',
			'jahrgaenge',
			'faecher',
			'floskelgruppen',
			'lerngruppen',
			'teilleistungsarten',
			'schueler'
		];

		$service = new DataImportService(data: request()->only(keys: $keys));
		$service->import();

		Lehrer::all()->each(callback: fn (Lehrer $lehrer) => $lehrer->update(
			attributes: ['password' => Hash::make(value: 'password')])
		);
    }
}
