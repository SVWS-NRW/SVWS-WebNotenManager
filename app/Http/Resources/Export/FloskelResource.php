<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class FloskelResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'kuerzel' => $this->kuerzel,
            'text' => $this->text,
            'fachID' => $this->fach?->ext_id,
            'niveau' => $this->niveau,
            'jahrgangID' => $this->jahrgang?->ext_id,
        ];
    }
}
