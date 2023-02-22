<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class SchuelerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'leistungsdaten' => LeistungsdatenResource::collection(resource: $this->leistungen),
            'lernabschnitt' => new LernabschnittResource(resource: $this->lernabschnitt),
            'bemerkungen' => new BemerkungResource(resource: $this->bemerkung),
        ];
    }
}
