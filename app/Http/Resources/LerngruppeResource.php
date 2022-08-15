<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LerngruppeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'kursartID' => $this->kursartID,
            'bezeichnung' => $this->bezeichnung,
            'fach_id' => $this->fach_id,
        ];
    }
}
