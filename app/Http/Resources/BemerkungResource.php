<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BemerkungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'asv' => (bool) $this->asv,
            'aue' => (bool) $this->aue,
            'zb' => (bool) $this->zb,
        ];
    }
}
