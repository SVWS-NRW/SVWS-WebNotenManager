<?php

namespace App\Http\Resources;

use App\Models\Klasse;
use App\Models\Kurs;
use Illuminate\Http\Resources\Json\JsonResource;

class LeistungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
			'id' => $this->id,
			'klasse' => $this->schueler->klasse->kuerzel,
			'name' => "{$this->schueler->nachname}, {$this->schueler->vorname}",
			'vorname' => $this->schueler->vorname,
			'nachname' => $this->schueler->nachname,
			'geschlecht' => $this->schueler->geschlecht,
			'fach' => $this->lerngruppe->fach->kuerzelAnzeige,
			'fach_id' => $this->lerngruppe->fach->id,
			'jahrgang' => $this->schueler->jahrgang->kuerzel,
			'lehrer' => $this->lerngruppe->lehrer->pluck(value: 'kuerzel')->implode(', '),
			'kurs' => $this->getKurs(),
			'note' => $this->note?->kuerzel,
			'istGemahnt' => $this->istGemahnt,
			'mahndatum' => $this->mahndatum,
			'fs' => $this->fehlstundenGesamt,
			'ufs' => $this->fehlstundenUnentschuldigt,
			'fachbezogeneBemerkungen' => $this->fachbezogeneBemerkungen,
        ];
    }

	private function getKurs(): string|null
	{
		if ($this->lerngruppe->kursartID) {
			return $this->lerngruppe->bezeichnung;
		}

		return null;
	}
}
