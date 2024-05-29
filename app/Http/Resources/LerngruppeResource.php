<?php

namespace App\Http\Resources;

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
            'kursartID' => $this->kursartID,
            'bezeichnung' => $this->bezeichnung,
            'fach_id' => $this->fach_id,
        ];
    }
}
