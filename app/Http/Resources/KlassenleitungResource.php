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
			'vorname' => $this->vorname,
			'nachname' => $this->nachname,
			'klasse' => $this->klasse->kuerzel,
			'asv' => $this->bemerkung?->asv,
			'aue' => $this->bemerkung?->aue,
			'zb' => $this->bemerkung?->zb,
			'gfs' => $this->leistungen->sum('fehlstundenGesamt'),
			'gfsu' => $this->leistungen->sum('fehlstundenUnentschuldigt'),
		];
    }
}