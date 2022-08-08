<?php

namespace App\Http\Resources;

use App\Models\Klasse;
use App\Models\Kurs;
use Illuminate\Http\Resources\Json\JsonResource;

class LeistungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'klasse' => new KlasseResource($this->lerngruppe(Klasse::class)),
            'schueler' => new SchuelerResource($this->schueler),
//            'fach' => $this->lerngruppe->fach->kuerzel,
            'lehrer' => $this->schueler->klasse->klassenlehrer->pluck('nachname')->implode(', '),
//            'kurs' => new KursResource($this->lerngruppe(Kurs::class)),
            'note' => new NoteResource($this->note),
            'fs' => $this->fehlstundenGesamt,
            'ufs' => $this->fehlstundenUnentschuldigt,
            'mahnung' => (bool) rand(0,1), // TODO: Mahnung fehlt
        ];
    }

    private function lerngruppe(string $class): Klasse|Kurs|null
    {
        if ($this->lerngruppe->groupable instanceof $class) {
            return $this->lerngruppe->groupable;
        }

        return null;
    }
}
