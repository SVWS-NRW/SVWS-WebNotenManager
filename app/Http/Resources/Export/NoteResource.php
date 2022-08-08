<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'kuerzel' => $this->kuerzel,
            'notenpunkte' => $this->notenpunkte,
            'text' => $this->text,
        ];
    }
}
