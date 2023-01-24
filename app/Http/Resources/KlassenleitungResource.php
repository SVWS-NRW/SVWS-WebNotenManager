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
			'geschlecht' => $this->geschlecht,
			'klasse' => $this->klasse->kuerzel,
			'ASV' => $this->bemerkung?->ASV,
			'AUE' => $this->bemerkung?->AUE,
			'ZB' => $this->bemerkung?->ZB,
			'gfs' => $this->leistungen->sum(callback: 'fehlstundenGesamt'),
			'gfsu' => $this->leistungen->sum(callback: 'fehlstundenUnentschuldigt'),
		];
    }
}
