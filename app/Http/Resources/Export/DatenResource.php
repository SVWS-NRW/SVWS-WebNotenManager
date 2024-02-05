<?php

namespace App\Http\Resources\Export;

use App\Models\Schueler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The `DatenResource` class is a JSON resource for formatting and presenting 'Daten' data.
 *
 * @package App\Http\Resources\Export
 */
class DatenResource extends JsonResource
{
    /**
     * Transform the data into a JSON array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
		$schueler = Schueler::query()
			->with(['leistungen', 'bemerkung'])
			->whereHas('leistungen', fn (Builder $leistung): Builder =>
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
        ];
    }
}
