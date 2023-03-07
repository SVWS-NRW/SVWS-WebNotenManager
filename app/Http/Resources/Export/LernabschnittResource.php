<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class LernabschnittResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
			'fehlstundenGesamt' => $this->fehlstundenGesamt,
			'tsFehlstundenGesamt' => $this->tsFehlstundenGesamt,
			'fehlstundenGesamtUnentschuldigt' => $this->fehlstundenGesamtUnentschuldigt,
			'tsFehlstundenGesamtUnentschuldigt' => $this->tsFehlstundenGesamtUnentschuldigt,
            'lernbereich1note' => $this->lernbereich1Note?->kuerzel,
            'lernbereich2note' => $this->lernbereich2Note?->kuerzel,
            'foerderschwerpunkt1' => $this->foerderschwerpunkt1Relation?->kuerzel,
            'foerderschwerpunkt2' => $this->foerderschwerpunkt2Relation?->kuerzel,
        ];
    }
}
