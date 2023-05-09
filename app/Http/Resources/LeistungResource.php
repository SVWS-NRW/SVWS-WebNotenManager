<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeistungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
			'id' => $this->id,
			'klasse' => $this->schueler->klasse->kuerzel,
			'matrix' => new MatrixResource($this->schueler->klasse),
			'name' => "{$this->schueler->nachname}, {$this->schueler->vorname}",
			'vorname' => $this->schueler->vorname,
			'nachname' => $this->schueler->nachname,
			'geschlecht' => $this->schueler->geschlecht,
			'fach' => $this->lerngruppe->fach->kuerzelAnzeige,
			'fach_id' => $this->lerngruppe->fach->id,
			'jahrgang' => $this->schueler->jahrgang->kuerzel,
			'lehrer' => $this->lerngruppe->lehrer->pluck(value: 'kuerzel')->implode(', '),
			'kurs' => $this->lerngruppe->kursartID !== null ? $this->lerngruppe->bezeichnung : '',
			'note' => $this->note?->kuerzel,
			'istGemahnt' => $this->istGemahnt,
			'mahndatum' => $this->mahndatum,
			'fs' => $this->fehlstundenFach,
			'fsu' => $this->fehlstundenUnentschuldigtFach,
			'fachbezogeneBemerkungen' => $this->fachbezogeneBemerkungen,
        ];
    }
}
