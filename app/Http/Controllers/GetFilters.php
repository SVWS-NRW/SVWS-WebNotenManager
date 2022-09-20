<?php

namespace App\Http\Controllers;

use App\Models\Fach;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Note;
use Illuminate\Http\JsonResponse;

class GetFilters extends Controller
{
	const OPTION_ALL = ['index' => 0, 'label' => 'Alle'];
	const OPTION_EMPTY = ['index' => '', 'label' => 'Leer'];

	public function __invoke(): JsonResponse
	{
		return response()->json([
			'noten' => $this->getOptions(Note::class, true),
			'jahrgaenge' => $this->getOptions(Jahrgang::class, true, false),
			'klassen' => $this->getOptions(Klasse::class, true, true),
			'kurse' => $this->getOptions(Kurs::class, true, true),
			'faecher' => $this->getOptions(Fach::class, true, false),
		]);
	}

	private function getOptions(string $class, bool $showAllOption = false, bool $showEmptyOption = false): array
	{
		$options = [];

		if ($showAllOption) {
			$options = array_merge($options, [self::OPTION_ALL]);
		}

		// Add the empty option only if it's not already in the collection
		$emptyIsNotInCollection = (new $class)->where('kuerzel', '=', '')->doesntExist();

		if ($showEmptyOption && $emptyIsNotInCollection) {
			$options = array_merge($options, [self::OPTION_EMPTY]);
		}

		$modelOptions = (new $class)
			->get(['kuerzel as index', 'kuerzel as label'])
			->map(function (Note|Jahrgang|Klasse|Kurs|Fach $model) {
				if ($model->label == '') {
					$model->label = self::OPTION_EMPTY['label'];
				}

				return $model;
			})
			->toArray();

		return array_merge($options, $modelOptions);
	}
}
