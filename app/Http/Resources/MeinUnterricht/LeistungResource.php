<?php

namespace App\Http\Resources\MeinUnterricht;

use Illuminate\Http\Resources\Json\JsonResource;

class LeistungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'klasse' => $this->schueler->klasse->kuerzelAnzeige,
            'name' => "{$this->schueler->nachname}, {$this->schueler->vorname}",
            'vorname' => $this->schueler->vorname,
            'nachname' => $this->schueler->nachname,
            'geschlecht' => $this->schueler->geschlecht,
            'fach' => $this->lerngruppe->fach->kuerzelAnzeige,
            'fach_id' => $this->lerngruppe->fach->id,
            'jahrgang' => $this->schueler->jahrgang->kuerzel,
            'lehrer' => $this->lerngruppe->lehrer->pluck('kuerzel')->implode(', '),
            'kurs' => $this->lerngruppe->kursartID !== null ? $this->lerngruppe->bezeichnung : '',
            'note' => $this->note?->kuerzel,
            'istGemahnt' => $this->istGemahnt,
            'mahndatum' => $this->mahndatum,
            'fs' => $this->fehlstundenFach,
            'fsu' => $this->fehlstundenUnentschuldigtFach,
            'fachbezogeneBemerkungen' => $this->fachbezogeneBemerkungen,
            'editable' => new MatrixResource($this->schueler->klasse)
        ];
    }
}
