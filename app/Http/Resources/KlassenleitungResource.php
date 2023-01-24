<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KlassenleitungResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
			'id' => $this->id,
			'name' => "{$this->nachname}, {$this->vorname}",
			'geschlecht' => $this->geschlecht,
			'klasse' => $this->klasse->kuerzel,
			'ASV' => $this->bemerkung?->ASV,
			'AUE' => $this->bemerkung?->AUE,
			'ZB' => $this->bemerkung?->ZB,
			'gfs' => $this->leistungen->sum('fehlstundenGesamt'),
			'gfsu' => $this->leistungen->sum('fehlstundenUnentschuldigt'),
		];
    }
}
