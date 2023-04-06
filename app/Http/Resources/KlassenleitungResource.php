<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KlassenleitungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
			'id' => $this->id,
			'nachname' => $this->nachname,
			'vorname' => $this->vorname,
			'name' => "{$this->nachname}, {$this->vorname}",
			'geschlecht' => $this->geschlecht,
			'klasse' => $this->klasse->kuerzel,
			'matrix' => new MatrixResource($this->klasse),
			'ASV' => $this->bemerkung?->ASV,
			'AUE' => $this->bemerkung?->AUE,
			'ZB' => $this->bemerkung?->ZB,
			'gfs' => $this->lernabschnitt?->fehlstundenGesamt,
			'gfsu' => $this->lernabschnitt?->fehlstundenGesamtUnentschuldigt,
		];
    }
}
