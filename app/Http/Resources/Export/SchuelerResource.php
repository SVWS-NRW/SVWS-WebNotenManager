<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `SchuelerResource` class is a JSON resource for formatting and presenting 'Schueler' data.
 *
 * @package App\Http\Resources\Export
 */
class SchuelerResource extends JsonResource
{
    /**
     * Transform the data into a JSON array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'jahrgangID' => $this->jahrgang->idJahrgang,
            'klasseID' => $this->klasse->id,
            'nachname' => $this->nachname,
            'vorname' => $this->vorname,
            'geschlecht' => $this->geschlecht,
            'bilingualeSprache' => $this->bilingualeSprache?->kuerzel,
            'istZieldifferent' => $this->istZieldifferent,
            'istDaZFoerderung' => $this->istDaZFoerderung,
            'leistungsdaten' => LeistungsdatenResource::collection($this->leistungen),
            'lernabschnitt' => new LernabschnittResource($this->lernabschnitt),
            'bemerkungen' => new BemerkungResource($this->bemerkung),
        ];
    }
}
