<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class Zp10Resource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'fachID' => $this->fach->id,
            'vornote' => $this->vornote?->kuerzel,
            'noteSchriftlichePruefung' => $this->noteSchriftlichePruefung?->kuerzel,
            'muendlichePruefung' => $this->muendlichePruefung,
            'muendlichePruefungFreiwillig' => $this->muendlichePruefungFreiwillig,
            'noteMuendlichePruefung' => $this->noteMuendlichePruefung?->kuerzel,
            'abschlussnote' => $this->abschlussnote?->kuerzel,
        ];
    }
}
