<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class BemerkungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'ASV' => $this->asv,
            'AUE' => $this->aue,
            'ZB' => $this->zb,
            'LELS' => $this->lels,
			'created_at' => $this->created_at->format('Y-m-d H:i:s'),
			'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
