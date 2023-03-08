<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class LeistungsdatenResource extends JsonResource
{
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