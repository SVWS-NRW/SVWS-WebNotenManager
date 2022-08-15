<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class FloskelgruppeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'kuerzel' => $this->kuerzel,
            'bezeichnung' => $this->bezeichnung,
            'hauptgruppe' => $this->hauptgruppe,
            'floskeln' => FloskelResource::collection($this->floskeln),
        ];
    }
}
