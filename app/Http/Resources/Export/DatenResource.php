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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

class DatenResource extends JsonResource
{
    public function toArray($request): array
    {
		$schueler = Schueler::query()
			->with('leistungen', 'bemerkung')
			->whereHas('leistungen', fn (Builder $leistung) =>
				$leistung->whereIn('lerngruppe_id', $this->lehrer->lerngruppen->pluck('id')->toArray())
			)
			->get();

        return [
            'enmRevision' => $this->enmRevision,
            'schuljahr' => $this->schuljahr,
            'anzahlAbschnitte' => $this->anzahlAbschnitte,
            'aktuellerAbschnitt' => $this->aktuellerAbschnitt,
			'schulform' => $this->schulform,
			'lehrerID' => $this->lehrer->ext_id,
            'schueler' => SchuelerResource::collection($schueler),


//            'noten' => $this->attributes['noten'],
//            'foerderschwerpunkte' => $this->attributes['foerderschwerpunkte'],
//            'faecher' => $this->attributes['faecher'],
//            'jahrgaenge' => $this->attributes['jahrgaenge'],
//
////            'jahrgaenge' => JahrgangResource::collection(Jahrgang::all()),
////            'klassen' => FloskelgruppeResource::collection(Klasse::all()),
//
//            'floskelgruppen' => $this->attributes['floskelgruppen'],
////            'lehrer' => LehrerResource::collection(Lehrer::all()),
////            'faecher' => FachResource::collection(Fach::all()),
//
//            'teilleistungsarten' => $this->attributes['teilleistungsarten'],

//            'lerngruppen' => LerngruppeResource::collection($this->lerngruppen),

        ];
    }
}
