<?php

namespace App\Http\Resources\Export;

use App\Models\Klasse;
use App\Models\Kurs;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `LerngruppeResource` class is a JSON resource for formatting and presenting 'Lerngruppe' data.
 *
 * @package App\Http\Resources\Export
 */
class LerngruppeResource extends JsonResource
{
    /**
     * Transform the data into a JSON array.
     *
     * @param $request
     * @return array
     */
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

    /**
     * Get the ID of a 'Kurs' if the provided entity is an instance of 'Kurs'.
     *
     * @param Kurs|Klasse $groupable
     * @return int|null
     */
    private function getKursId(Kurs|Klasse $groupable): int|null
    {
        return $groupable instanceof Kurs ? $groupable->ext_id : null;
    }
}
