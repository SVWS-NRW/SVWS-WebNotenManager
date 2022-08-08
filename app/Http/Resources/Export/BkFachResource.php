<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class BkFachResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'fachID' => $this->fach->id,
            'lehrerID' => $this->lehrer->id,
            'istSchriftlich' => $this->istSchriftlich,
            'vornote' => $this->vornote, // TODO: note
            'noteSchriftlichePruefung' => $this->noteSchriftlichePruefung, // TODO: note
            'muendlichePruefung' => $this->muendlichePruefung,
            'muendlichePruefungFreiwillig' => $this->muendlichePruefungFreiwillig,
            'noteMuendlichePruefung' => $this->noteMuendlichePruefung, // TODO: note
            'istSchriftlichBerufsabschluss' => $this->istSchriftlichBerufsabschluss,
            'noteBerufsabschluss' => $this->noteBerufsabschluss, // TODO: note
            'abschlussnote' => $this->abschlussnote, // TODO: note
        ];
    }
}
