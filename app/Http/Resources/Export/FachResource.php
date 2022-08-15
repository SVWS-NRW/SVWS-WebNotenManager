<?php

namespace App\Http\Resources\Export;

use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Foerderschwerpunkt;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Lerngruppe;
use App\Models\Note;
use App\Models\Schueler;
use App\Models\Teilleistungsart;
use App\Models\User as Lehrer;
use Illuminate\Http\Resources\Json\JsonResource;

class FachResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ext_id,
            'kuerzel' => $this->kuerzel,
            'kuerzelAnzeige' => $this->kuerzelAnzeige,
            'sortierung' => $this->sortierung,
            'istFremdsprache' => (bool) $this->istFremdsprache,
        ];
    }
}
