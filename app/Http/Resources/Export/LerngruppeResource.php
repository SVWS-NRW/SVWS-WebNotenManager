<?php

namespace App\Http\Resources\Export;

use App\Models\Klasse;
use App\Models\Kurs;
use Illuminate\Http\Resources\Json\JsonResource;

class LerngruppeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ext_id,
            'kID' => $this->groupable->ext_id,
            'fachID' => $this->fach?->id,
            'kursartID' => $this->getKursId($this->groupable),
            'bezeichnung' => $this->bilingualeSprache,
            'kursartKuerzel' => $this->kuerzel,
            'bilingualeSprache' => $this->bilingualeSprache,
            'lehrerID' => $this->lehrer->pluck('ext_id'),
            'wochenstunden' => $this->wochenstunden,
        ];
    }

    private function getKursId(Kurs|Klasse $groupable): int|null
    {
        return $groupable instanceof Kurs ? $groupable->ext_id : null;
    }
}
