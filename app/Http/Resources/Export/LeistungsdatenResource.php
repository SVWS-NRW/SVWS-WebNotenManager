<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `LeistungsdatenResource` class is a JSON resource for formatting and presenting 'Leistungsdaten' data.
 *
 * @package App\Http\Resources\Export
 */
class LeistungsdatenResource extends JsonResource
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
            'id' => $this->id,
            'note' => $this->note?->kuerzel,
            'tsNote' => $this->tsNote,
            'fehlstundenFach' => $this->fehlstundenFach,
            'tsFehlstundenFach' => $this->tsFehlstundenFach,
            'fehlstundenUnentschuldigtFach' => $this->fehlstundenUnentschuldigtFach,
            'tsFehlstundenUnentschuldigtFach' => $this->tsFehlstundenUnentschuldigtFach,
			'fachbezogeneBemerkungen' => $this->fachbezogeneBemerkungen,
			'tsFachbezogeneBemerkungen' => $this->tsFachbezogeneBemerkungen,
			'istGemahnt' => (bool) $this->istGemahnt,
			'tsIstGemahnt' => $this->tsIstGemahnt,
        ];
    }
}