<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class SchuelerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ext_id,
            'leistungsdaten' => LeistungsdatenResource::collection($this->leistungen),
            'bemerkungen' => new BemerkungResource($this->bemerkung),
        ];
    }
}
