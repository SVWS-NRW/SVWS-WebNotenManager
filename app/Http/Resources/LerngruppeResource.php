<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LerngruppeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'kursartID' => $this->kursartID,
            'bezeichnung' => $this->bezeichnung,
            'fach_id' => $this->fach_id,
        ];
    }
}
