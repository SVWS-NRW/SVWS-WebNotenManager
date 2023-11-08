<?php

namespace App\Http\Resources\MeinUnterricht;

use Illuminate\Http\Resources\Json\JsonResource;

class MatrixResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'fehlstunden' => $this->permission($this->editable_fehlstunden & $this->toggleable_fehlstunden),
            'asv' => $this->permission($this->editable_asv),
            'aue' => $this->permission($this->editable_aue),
            'zb' => $this->permission($this->editable_zb),
        ];
    }

    private function permission(bool $condition = false): bool
    {
        return auth()->user()->isAdministrator() || $condition;
    }
}
