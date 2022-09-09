<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class LeistungsdatenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ext_id,
            'note' => $this->note?->kuerzel,
            'istGemahnt' => (bool) $this->istGemahnt,
            'mahndatum' => $this->mahndatum?->format('Y-m-d\TH:i:s'),
			'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s'),
        ];
    }
}