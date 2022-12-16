<?php

namespace App\Http\Resources\Export;

use App\Models\Schueler;
use Illuminate\Http\Resources\Json\JsonResource;

class BemerkungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'ASV' => $this->ASV ? $this->formatBemerkung($this->schueler, $this->ASV) : null,
            'tsASV' => $this->tsASV,
            'AUE' => $this->AUE ? $this->formatBemerkung($this->schueler, $this->AUE) : null,
            'tsAUE' => $this->tsAUE,
            'ZB' => $this->ZB ? $this->formatBemerkung($this->schueler, $this->ZB) : null,
            'tsZB' => $this->tsZB,
            'LELS' => $this->LELS,
            'schulformEmpf' => $this->schulformEmpf,
            'individuelleVersetzungsbemerkungen' => $this->individuelleVersetzungsbemerkungen ? $this->formatBemerkung($this->schueler, $this->individuelleVersetzungsbemerkungen) : null,
            'tsIndividuelleVersetzungsbemerkungen' => $this->tsIndividuelleVersetzungsbemerkungen,
            'foerderbemerkungen' => $this->foerderbemerkungen,
        ];
    }

	private function formatBemerkung(Schueler $schueler, string $bemerkung): string
	{
		$firstOccurrence = true;

		$pattern = '/(\$vorname\$ \$nachname\$|\$vorname\$|\$nachname\$)/i';
		$text = preg_split($pattern, $bemerkung, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		$pronouns = ['m' => 'Er', 'w' => 'Sie'];
		$pronoun = array_key_exists($schueler->geschlecht, $pronouns) ? $pronouns[$schueler->geschlecht] : null;

		$initialOccurrence = [
			'$vorname$ $nachname$' => "{$schueler->vorname} {$schueler->nachname}",
			'$vorname$' => $schueler->vorname,
			'$nachname$' => $schueler->nachname,
		];

		$succeedingOccurrences = [
			'$vorname$ $nachname$' => $pronoun ?? $schueler->vorname,
			'$vorname$' => $pronoun ?? $schueler->vorname,
			'$nachname$' => null,
		];

		foreach ($text as &$item) {
			if (array_key_exists(strtolower($item), $array = $firstOccurrence ? $initialOccurrence : $succeedingOccurrences)) {
				$item = $array[strtolower($item)];
				$firstOccurrence = false;
			}
		}

		return implode($text);
	}
}
