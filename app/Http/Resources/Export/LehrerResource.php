<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class LehrerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ext_id,
            'kuerzel' => $this->kuerzel,
            'nachname' => $this->nachname,
            'vorname' => $this->vorname,
            'eMailDienstlich' => $this->email,
        ];
    }
}
