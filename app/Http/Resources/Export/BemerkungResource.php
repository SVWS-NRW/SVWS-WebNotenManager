<?php

namespace App\Http\Resources\Export;

use App\Models\Schueler;
use Illuminate\Http\Resources\Json\JsonResource;

class BemerkungResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'ASV' => $this->asv ? $this->formatBemerkung($this->schueler, $this->asv) : null,
            'AUE' => $this->aue ? $this->formatBemerkung($this->schueler, $this->aue) : null,
            'ZB' => $this->zb ? $this->formatBemerkung($this->schueler, $this->zb) : null,
            'LELS' => $this->lels ? $this->formatBemerkung($this->schueler, $this->lels) : null,
			'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s'),
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
