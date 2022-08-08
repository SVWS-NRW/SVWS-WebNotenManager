<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class TeilleistungsartResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ext_id,
            'bezeichnung' => $this->bezeichnung,
            'sortierung' => $this->sortierung,
            'gewichtung' => $this->gewichtung,
        ];
    }
}
