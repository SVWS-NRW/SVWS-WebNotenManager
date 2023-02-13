<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FloskelResource extends JsonResource
{
    public function toArray($request): array
    {
        return[
            'id' => $this->id,
            'gruppe' => $this->floskelgruppe->hauptgruppe,
            'text' => $this->text,
            'kuerzel' => $this->kuerzel,
        ];
    }
}
