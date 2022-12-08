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
			'vorname' => $this->schueler->vorname,
			'nachname' => $this->schueler->nachname,
			'geschlecht' => $this->schueler->geschlecht,
			'fach' => $this->lerngruppe->fach->kuerzelAnzeige,
			'jahrgang' => $this->schueler->jahrgang->kuerzel,
			'lehrer' => $this->lerngruppe->lehrer->pluck('nachname')->implode(', '),
			'kurs' => $this->lerngruppe->bezeichnung,
			'note' => $this->note?->kuerzel,
			'istGemahnt' => (bool) $this->istGemahnt,
			'mahndatum' => $this->mahndatum,
			'fs' => $this->fehlstundenGesamt,
			'ufs' => $this->fehlstundenUnentschuldigt,
			'fachbezogeneBemerkungen' => $this->fachbezogeneBemerkungen,
        ];
    }
}
