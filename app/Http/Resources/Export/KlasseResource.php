<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class KlasseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ext_id,
            'kuerzel' => $this->kuerzel,
            'kuerzelAnzeige' => $this->kuerzelAnzeige,
            'sortierung' => $this->sortierung,
            'klassenlehrer' => $this->klassenlehrer->pluck('id'),
        ];
    }
}
