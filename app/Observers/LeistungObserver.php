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
			'klasse' => $this->getMorphable($leistung->lerngruppe, Klasse::class),
			'vorname' => $leistung->schueler->vorname,
			'nachname' => $leistung->schueler->nachname,
			'geschlecht' => $leistung->schueler->geschlecht,
			'fach' => $leistung->lerngruppe->fach->kuerzelAnzeige,
			'jahrgang' => $leistung->schueler->jahrgang->kuerzel,
			'lehrer' => $leistung->lerngruppe->lehrer->pluck('nachname')->implode(', '),
			'kurs' => $this->getMorphable($leistung->lerngruppe, Kurs::class),
			'note' => $leistung->note?->kuerzel,
			'istGemahnt' => (bool) $leistung->istGemahnt,
			'mahndatum' => $leistung->mahndatum,
			'fs' => $leistung->fehlstundenGesamt,
			'ufs' => $leistung->fehlstundenUnentschuldigt,
		];
	}

	private function getMorphable(Lerngruppe $lerngruppe, string $class): string|null
	{
		if ($lerngruppe->groupable instanceof $class) {
			return $lerngruppe->groupable->kuerzel;
		}

		return null;
	}
}
