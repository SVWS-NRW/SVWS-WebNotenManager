<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeinUnterricht\LeistungResource;
use App\Models\Leistung;
use App\Models\UserSetting;
use App\Settings\FilterSettings;
use Illuminate\Database\Eloquent\Builder;

class MeinUnterricht extends Controller
{
	public function __invoke()
	{
		$eagerLoadedColumns = [
			'schueler' => ['klasse', 'jahrgang'],
			'lerngruppe' => ['lehrer', 'fach'],
			'note',
		];

		$leistungen = Leistung::query()
			->with($eagerLoadedColumns)
			->when(
				auth()->user()->isLehrer(),
				fn (Builder $query): Builder => $query->whereHas('lerngruppe',
					fn (Builder $query): Builder => $query->whereIn('id', auth()->user()->lerngruppen->pluck(value: 'id')->toArray()
					)
				)
			)
			->get()
			->sortBy([
                'schueler.klasse.kuerzel', 'schueler.nachname', 'lerngruppe.fach.kuerzelAnzeige',
            ]);

		return LeistungResource::collection($leistungen)->additional([
            'toggles' => auth()->user()->filters('meinunterricht'),
        ]);
	}
}
