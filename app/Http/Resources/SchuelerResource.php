<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchuelerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => implode(separator: ', ', array: [$this->nachname, $this->vorname]),
            'vorname' => $this->vorname,
            'nachname' => $this->nachname,
            'geschlecht' => $this->geschlecht,
            'jahrgang_id' => $this->jahrgang->id,
            'bemerkungen' => new BemerkungResource(resource: $this->bemerkung),
        ];
    }
}
