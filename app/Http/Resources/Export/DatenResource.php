<?php

namespace App\Http\Resources\Export;


use App\Models\Schueler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

class DatenResource extends JsonResource
{
    public function toArray($request): array
    {
		$schueler = Schueler::query()
			->with(relations: ['leistungen', 'bemerkung'])
			->whereHas(relation: 'leistungen', callback: fn (Builder $leistung): Builder =>
				$leistung->whereIn(
					column:'lerngruppe_id',
					values: $this->lehrer->lerngruppen->pluck(key: 'id')->toArray()
				)
			)
			->get();

        return [
            'enmRevision' => $this->enmRevision,
            'schuljahr' => $this->schuljahr,
            'anzahlAbschnitte' => $this->anzahlAbschnitte,
            'aktuellerAbschnitt' => $this->aktuellerAbschnitt,
			'schulform' => $this->schulform,
			'lehrerID' => $this->lehrer->ext_id,
            'schueler' => SchuelerResource::collection(resource: $schueler),
        ];
    }
}
