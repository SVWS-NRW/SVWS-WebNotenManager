<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\JsonResource;

class BkAbschlussResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'hatZulassung' => $this->hatZulassung,
            'hatBestanden' => $this->hatBestanden,
            'hatZulassungErweiterteBeruflicheKenntnisse' => $this->hatZulassungErweiterteBeruflicheKenntnisse,
            'hatErworbenErweiterteBeruflicheKenntnisse' => $this->hatErworbenErweiterteBeruflicheKenntnisse,
            'notePraktischePruefung' => $this->notePraktischePruefung?->ext_id,
            'noteKolloqium' => $this->noteKolloqium?->ext_id,
            'hatZulassungBerufsabschlusspruefung' => $this->hatZulassungBerufsabschlusspruefung,
            'hatBestandenBerufsabschlusspruefung' => $this->hatBestandenBerufsabschlusspruefung,
            'themaAbschlussarbeit' => $this->themaAbschlussarbeit,
            'istVorhandenBerufsabschlusspruefung' => $this->istVorhandenBerufsabschlusspruefung,
            'noteFachpraxis' => $this->noteFachpraxis?->ext_id,
            'istFachpraktischerTeilAusreichend' => $this->istFachpraktischerTeilAusreichend,
            'faecher' => 123
        ];
    }
}
