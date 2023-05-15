<?php

namespace App\Http\Resources\Matrix;

use Illuminate\Http\Resources\Json\JsonResource;

class KlasseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
			'id' => $this->id,
			'kuerzel' => $this->kuerzel,
			'sortierung' => $this->sortierung,
			'editable_teilnoten' => $this->editable_teilnoten,
			'editable_noten' => $this->editable_noten,
			'editable_mahnungen' => $this->editable_mahnungen,
			'editable_fehlstunden' => $this->editable_fehlstunden,
			'toggleable_fehlstunden' => $this->toggleable_fehlstunden,
			'editable_fb' => $this->editable_fb,
			'editable_asv' => $this->editable_asv,
			'editable_aue' => $this->editable_aue,
			'editable_zb' => $this->editable_zb,
		];
    }
}
