<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FachBezogeneFloskelResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
			'kuerzel' => $this->kuerzel,
			'text' => $this->text,
			'niveau' => $this->niveau,
			'jahrgang' => $this->jahrgang?->kuerzel,
		];
    }
}
