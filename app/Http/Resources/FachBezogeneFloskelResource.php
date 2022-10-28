<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FachBezogeneFloskelResource extends JsonResource
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
			'kuerzel' => $this->kuerzel,
			'text' => $this->text,
			'niveau' => $this->niveau,
			'jahrgang_id' => $this->jahrgang_id,
		];
    }
}
