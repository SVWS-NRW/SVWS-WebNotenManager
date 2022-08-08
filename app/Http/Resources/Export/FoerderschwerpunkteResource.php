<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class FoerderschwerpunkteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'kuerzel' => $this->kuerzel,
            'beschreibung' => $this->beschreibung,
        ];
    }
}
