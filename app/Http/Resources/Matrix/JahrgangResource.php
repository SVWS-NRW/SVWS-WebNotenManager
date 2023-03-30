<?php

namespace App\Http\Resources\Matrix;

use Illuminate\Http\Resources\Json\JsonResource;

class JahrgangResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
			'id' => $this->id,
			'kuerzel' => $this->kuerzel,
			'stufe' => $this->stufe,
			'klassen' => KlasseResource::collection(resource: $this->klassen)
		];
    }
}
