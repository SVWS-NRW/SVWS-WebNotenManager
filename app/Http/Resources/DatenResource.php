<?php

namespace App\Http\Resources;

use App\Models\Schueler;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get the data with all relations.
        $schueler = Schueler::with([
            'bemerkung',
            'leistungen' => [
                'note',
            ],
            'lernabschnitt' => [
                'lernbereich1Note', 'lernbereich2Note', 'foerderschwerpunkt1Relation', 'foerderschwerpunkt2Relation',
            ],
        ])
        ->get();

        return [
            'schulnummer' => config('wenom.schulnummer'),
            'enmRevision' => 1,
            'schuljahr' => 2021,
            'anzahlAbschnitte' => 2,
            'aktuellerAbschnitt' => 2,
            'publicKey' => 'string',
            'lehrerID' => 42,
            'fehlstundenEingabe' => true,
            'fehlstundenSIFachbezogen' => false,
            'fehlstundenSIIFachbezogen' => true,
            'schulform' => 'GY',
            'mailadresse' => 'mail@schule.nrw.de',
            'noten' => [],
            'foerderschwerpunkte' => [],
            'jahrgaenge' => [],
            'klassen' => [],
            'floskelgruppen' => [],
            'lehrer' => [],
            'teilleistungsarten' => [],
            'lerngruppen' => [],
            'schueler' => SchuelerResource::collection($schueler)
        ];
    }
}
