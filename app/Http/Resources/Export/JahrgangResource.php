<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class JahrgangResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ext_id,
            'kuerzel' => $this->kuerzel,
            'kuerzelAnzeige' => $this->kuerzelAnzeige,
            'beschreibung' => $this->beschreibung,
            'stufe' => $this->stufe,
            'sortierung' => $this->sortierung,
        ];
    }
}
