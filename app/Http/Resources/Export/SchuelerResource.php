<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class SchuelerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'leistungsdaten' => LeistungsdatenResource::collection($this->leistungen),
            'lernabschnitt' => new LernabschnittResource($this->lernabschnitt),
//            'bemerkungen' => new BemerkungResource($this->bemerkung),

        ];
    }
}
