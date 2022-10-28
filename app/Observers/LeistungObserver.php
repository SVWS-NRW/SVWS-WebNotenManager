<?php

namespace App\Observers;

use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Leistung;
use App\Models\Lerngruppe;

class LeistungObserver
{
    public function created(Leistung $leistung): void
    {
		$leistung->leistungNormalized()->create($this->getData($leistung));
    }

	private function getData(Leistung $leistung): array
	{
		return [
			'lerngruppe_id' => $leistung->lerngruppe->id,
			'klasse' => $leistung->schueler->klasse->kuerzel,
			'vorname' => $leistung->schueler->vorname,
			'nachname' => $leistung->schueler->nachname,
			'geschlecht' => $leistung->schueler->geschlecht,
			'fach' => $leistung->lerngruppe->fach->kuerzelAnzeige,
			'jahrgang' => $leistung->schueler->jahrgang->kuerzel,
			'lehrer' => $leistung->schueler->klasse->klassenlehrer->pluck('kuerzel')->implode(', '),
			'kurs' => $leistung->lerngruppe->kursartID !== null ? $leistung->lerngruppe->bezeichnung : null,
			'note' => $leistung->note?->kuerzel,
			'istGemahnt' => (bool) $leistung->istGemahnt,
			'mahndatum' => $leistung->mahndatum,
			'fs' => $leistung->fehlstundenGesamt,
			'ufs' => $leistung->fehlstundenUnentschuldigt,
		];
	}
}
