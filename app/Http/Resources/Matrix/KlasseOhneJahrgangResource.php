<?php

namespace App\Http\Resources\Matrix;

use Illuminate\Http\Resources\Json\JsonResource;

class KlasseOhneJahrgangResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
			'id' => 123,
			'kuerzel' => 345,
			'stufe' => 678,
			'klassen' => KlasseResource::collection($this->klassen)
		];
    }
}