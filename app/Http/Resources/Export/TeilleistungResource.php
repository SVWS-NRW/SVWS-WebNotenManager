<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class TeilleistungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ext_id,
            'artID' => $this->teilleistungsart?->ext_id,
            'datum' => $this->datum,
            'bemerkung' => $this->bemerkung,
            'notenKuerzel' => $this->note?->kuerzel
        ];
    }
}
