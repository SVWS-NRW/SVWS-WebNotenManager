<?php

namespace App\Http\Controllers;

use App\Models\Fach;
use App\Models\Floskel;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Lerngruppe;
use App\Models\Note;
use Illuminate\Http\JsonResponse;

class GetFilters extends Controller
{
	const OPTION_ALL = ['index' => 0, 'label' => 'Alle'];
	const OPTION_EMPTY = ['index' => '', 'label' => 'Leer'];

	public function dashboard(): JsonResponse
	{
		return response()->json([
			'noten' => $this->getOptions(class: Note::class, showAllOption: true),
			'jahrgaenge' => $this->getOptions(class: Jahrgang::class, showAllOption: true),
			'klassen' => $this->getOptions(class: Klasse::class, showAllOption: true, showEmptyOption: true),
			'kurse' => $this->getOptions(class: Lerngruppe::class, showAllOption: true, showEmptyOption: true, column: 'bezeichnung'),
			'faecher' => $this->getOptions(class: Fach::class, showAllOption: true),
		]);
	}

	public function klassenleitung(): JsonResponse
	{
		$options = [self::OPTION_ALL];
		$lehrerKlassen = auth()->user()->klassen()->pluck('id');

		$klassen = Klasse::query()
			->whereIn('id', $lehrerKlassen)
			->whereNotNull('kuerzel')
			->get(['kuerzel as index', 'kuerzel as label'])
			->toArray();

		return response()->json([
			'klassen' => array_merge($options, $klassen)
		]);
	}

	public function fachbezogeneFloskeln(): JsonResponse
	{
		return response()->json([
			'niveau' => $this->getOptions(class: Floskel::class, showAllOption: true, column: 'niveau'),
			'jahrgaenge' => $this->getOptions(class: Floskel::class, showAllOption: true, column: 'jahrgang_id'),
		]);
	}

	private function getOptions(
		mixed $class,
		bool $showAllOption = false,
		bool $showEmptyOption = false,
		string $column = 'kuerzel'
	): array {
		$options = [];

		if ($showAllOption) {
			$options = array_merge($options, [self::OPTION_ALL]);
		}

		// Add the empty option only if it's not already in the collection
		$emptyIsNotInCollection = (new $class)->where($column, '=', '')->doesntExist();

		if ($showEmptyOption && $emptyIsNotInCollection) {
			$options = array_merge($options, [self::OPTION_EMPTY]);
		}

		$modelOptions = (gettype($class) == 'string' ? (new $class) : $class)
			->whereNotNull($column)
			->distinct($column)
			->get(["{$column} as index", "$column as label"])
			->map(function ($model) {
				if ($model->label == '') {
					$model->label = self::OPTION_EMPTY['label'];
				}

				return $model;
			})
			->toArray();

		return array_merge($options, $modelOptions);
	}
}
