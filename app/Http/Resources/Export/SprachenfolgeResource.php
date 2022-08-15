<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class SprachenfolgeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'sprache' => $this->fach->kuerzel, // TODO: whats the difference between this nach fachkuerzel
            'fachID' => $this->fach->id,
            'fachKuerzel' => $this->fach->kuerzel,
            'reihenfolge' => $this->reihenfolge,
            'belegungVonJahrgang' => $this->belegungVonJahrgang,
            'belegungVonAbschnitt' => $this->belegungVonAbschnitt,
            'belegungBisJahrgang' => $this->belegungBisJahrgang,
            'belegungBisAbschnitt' => $this->belegungBisAbschnitt,
            'referenzniveau' => $this->referenzniveau,
            'belegungSekI' => $this->belegungSekI,
        ];
    }
}
