<?php

namespace App\Http\Resources\Klassenleitung;

use Illuminate\Http\Resources\Json\JsonResource;

class SchuelerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nachname' => $this->nachname,
            'vorname' => $this->vorname,
            'name' => "{$this->nachname}, {$this->vorname}",
            'geschlecht' => $this->geschlecht,
            'klasse' => $this->klasse->kuerzelAnzeige,
            'ASV' => $this->bemerkung?->ASV,
            'AUE' => $this->bemerkung?->AUE,
            'ZB' => $this->bemerkung?->ZB,
            'gfs' => $this->lernabschnitt?->fehlstundenGesamt,
            'gfsu' => $this->lernabschnitt?->fehlstundenGesamtUnentschuldigt,
            'editable' => new MatrixResource($this->klasse)
        ];
    }
}
