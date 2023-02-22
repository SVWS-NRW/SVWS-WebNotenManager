<?php

namespace App\Http\Resources\Export;

use App\Models\Schueler;
use Illuminate\Http\Resources\Json\JsonResource;

class BemerkungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'ASV' => $this->ASV ? $this->formatBemerkung(bemerkung: $this->ASV) : null,
            'tsASV' => $this->tsASV,
            'AUE' => $this->AUE ? $this->formatBemerkung(bemerkung: $this->AUE) : null,
            'tsAUE' => $this->tsAUE,
            'ZB' => $this->ZB ? $this->formatBemerkung(bemerkung: $this->ZB) : null,
            'tsZB' => $this->tsZB,
            'LELS' => $this->LELS,
            'schulformEmpf' => $this->schulformEmpf,
            'individuelleVersetzungsbemerkungen' => $this->individuelleVersetzungsbemerkungen
				? $this->formatBemerkung(bemerkung: $this->individuelleVersetzungsbemerkungen)
				: null,
            'tsIndividuelleVersetzungsbemerkungen' => $this->tsIndividuelleVersetzungsbemerkungen,
            'foerderbemerkungen' => $this->foerderbemerkungen,
        ];
    }

	private function formatBemerkung(string $bemerkung): string
	{
		$firstOccurrence = true;

		$pattern = '/(\$vorname\$ \$nachname\$|\$vorname\$|\$nachname\$)/i';

		$text = preg_split(
			pattern: $pattern,
			subject: $bemerkung,
			limit: -1,
			flags: PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
		);

		$pronouns = ['m' => 'Er', 'w' => 'Sie'];
		$pronoun = array_key_exists(key: $this->schueler->geschlecht, array: $pronouns)
			? $pronouns[$this->schueler->geschlecht]
			: null;

		$initialOccurrence = [
			'$vorname$ $nachname$' => "{$this->schueler->vorname} {$this->schueler->nachname}",
			'$vorname$' => $this->schueler->vorname,
			'$nachname$' => $this->schueler->nachname,
		];

		$succeedingOccurrences = [
			'$vorname$ $nachname$' => $pronoun ?? $this->schueler->vorname,
			'$vorname$' => $pronoun ?? $this->schueler->vorname,
			'$nachname$' => null,
		];

		foreach ($text as &$item) {
			$condition = array_key_exists(
				key: strtolower($item),
				array: $array = $firstOccurrence ? $initialOccurrence : $succeedingOccurrences
			);

			if ($condition) {
				$item = $array[strtolower(string: $item)];
				$firstOccurrence = false;
			}
		}

		return implode(separator: ' ', array: $text);
	}
}
