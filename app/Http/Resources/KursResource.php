<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KursResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'kuerzel' => $this->kuerzel,
            'bezeichnung' => $this->bezeichnung,
        ];
    }
}
