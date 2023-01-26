<?php

namespace App\Services;

//use App\Models\Daten;
use App\Models\Bemerkung;
use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Foerderschwerpunkt;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Leistung;
use App\Models\Lernabschnitt;
use App\Models\Lerngruppe;
use App\Models\Lehrer;
use App\Models\Note;
use App\Models\Schueler;
use App\Models\Teilleistungsart;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Schema;

class DataImportService
{
	private array $existingNoten = [];
	private array $existingFoerderschwerpunkte = [];
	private int $microtime;

    public function __construct(
		array $data = [],
		private array $lehrer = [],
		private array $foerderschwerpunkte = [],
		private array $klassen = [],
		private array $noten = [],
		private array $jahrgaenge = [],
		private array $faecher = [],
		private array $floskelgruppen = [],
		private array $lerngruppen = [],
		private array $teilleistungsarten = [],
		private array $schueler = [],
	) {
		$this->lehrer = $data['lehrer'] ?? [];
		$this->foerderschwerpunkte = $data['foerderschwerpunkte'] ?? [];
		$this->klassen = $data['klassen'] ?? [];
		$this->noten = $data['noten'] ?? [];
		$this->jahrgaenge = $data['jahrgaenge'] ?? [];
		$this->faecher = $data['faecher'] ?? [];
		$this->floskelgruppen = $data['floskelgruppen'] ?? [];
		$this->lerngruppen = $data['lerngruppen'] ?? [];
		$this->teilleistungsarten = $data['teilleistungsarten'] ?? [];
		$this->schueler = $data['schueler'] ?? [];
	}

	public function truncate(): void
	{
		$this->start('Truncate');

		// Remove the table drop in final
		Schema::disableForeignKeyConstraints();

		$tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

		foreach ($tableNames as $name) {
			//if you don't want to truncate migrations
			if ($name == 'migrations') {
				continue;
			}
			DB::table($name)->truncate();
		}

		Schema::enableForeignKeyConstraints();

		$this->stop();
	}

    public function import(): void
    {
        $this->importLehrer();
        $this->importKlassen();
        $this->importNoten();
        $this->importFoerderschwerpunkte();
        $this->importJahrgaenge();
        $this->importFaecher();
        $this->importFloskelgruppen();
        $this->importLerngruppen();
        $this->importTeilleistungsarten();
        $this->importSchueler();

		// TODO
//        $this->importDaten();
    }

	/**
	 * Creates the Lehrer model. The model can be updated anytime.
	 * If the model does not exist yet, sets a new password depending on if in production or not.
	 * Sets a random email if not provided.
	 *
	 * @return void
	 */
	public function importLehrer(): void
	{
		if (is_null($this->lehrer)) {
			return;
		}

		$this->start('Lehrer');

		foreach($this->lehrer as $row) {
			$row['email'] = $this->email($row['eMailDienstlich']);
			$row['geschlecht'] = $this->gender(data: $row, allowed: Lehrer::GENDERS);

			unset($row['eMailDienstlich']);

			try {
				Lehrer::findOrFail($row['id']);
			} catch (ModelNotFoundException $e) {
				$row['password'] = app()->environment('production') ? Str::random() : Hash::make('password');
				report($e);
			}

			Lehrer::updateOrCreate(['id' => $row['id']], $row);
		}

		$this->stop();
	}

	/**
	 * Creates the Klasse model. The model will not be updated with future requests.
	 * After creating the model, the Lehrer model relationships are created.
	 * The relationships are only being set once, and will not trigger at consecutive requests.
	 *
	 * @return void
	 */
	public function importKlassen(): void
	{
		if (is_null($this->klassen)) {
			return;
		}

		$this->start('Klassen');

		foreach($this->klassen as $row) {
			$klasse = Klasse::firstOrCreate(['id' => $row['id']], Arr::except($row, ['klassenlehrer']));

			if ($klasse->wasRecentlyCreated === true) {
				$klasse->klassenlehrer()->attach($row['klassenlehrer']);
			}
		}

		$this->stop();
	}

	/**
	 * Creates the Note model. The model will not be updated with future requests.
	 * Resources with an negative id are filtered out. Relatable models are nullable.
	 *
	 * TODO: All Models are stored in an array to be called by ID in different resources.
	 *
	 * @return void
	 */
	public function importNoten(): void
    {
		if (is_null($this->noten)) {
			return;
		}

		$this->start('Noten');

		collect($this->noten)
			->filter(fn (array $row) => $row['id'] >= 0)
			->each(fn (array $row) => Note::firstOrCreate(['id' => $row['id']], $row));

		$this->existingNoten = $this->getExistingNoten();

		$this->stop();
    }

	/**
	 * Creates the Foerderschwerpunkt model. The model will not be updated with future requests.
	 *
	 * TODO: All Models are stored in an array to be called by ID in different resources.
	 *
	 * @return void
	 */
	public function importFoerderschwerpunkte(): void
	{
		if (is_null($this->foerderschwerpunkte)) {
			return;
		}

		$this->start('Förderschwerpunkte');

		foreach ($this->foerderschwerpunkte as $row) {
			Foerderschwerpunkt::firstOrCreate(['id' => $row['id']], $row);
		}

		$this->existingFoerderschwerpunkte = $this->getExistingFoerderschwerpunkte();

		$this->stop();
	}

	private function getExistingFoerderschwerpunkte(): array
	{
		return Foerderschwerpunkt::query()
			->orderBy('kuerzel')
			->pluck('id', 'kuerzel')
			->toArray();
	}

	private function getExistingNoten(): array
	{
		return Note::query()
			->orderBy('kuerzel')
			->pluck('id', 'kuerzel')
			->toArray();
	}

	/**
	 * Creates the Jahrgang model. The model will not be updated with future requests.
	 *
	 * TODO: On customers site there were formatting problems with whitespaces.
	 *
	 * @return void
	 */
	public function importJahrgaenge(): void
	{
		if (is_null($this->jahrgaenge)) {
			return;
		}

		$this->start('Jahrgänge');

		foreach ($this->jahrgaenge as $row) {
			$row['beschreibung'] = $this->trimWhitespaces($row['beschreibung']); // TODO: Check with customer
			Jahrgang::firstOrCreate(['id' => $row['id']], $row);
		}

		$this->stop();
	}

	/**
	 * Creates the Fach model. The model will not be updated with future requests.
	 *
	 * @return void
	 */
	public function importFaecher(): void
	{
		if (is_null($this->faecher)) {
			return;
		}

		$this->start('Fächer');

		foreach($this->faecher as $row) {
			Fach::firstOrCreate(['id' => $row['id']], $row);
		}

		$this->stop();
	}

	/**
	 * Creates the Floskelgruppe model. The model will not be updated with future requests.
	 * Related floskel will be created only if the Floskelgruppe model was recently created.
	 *
	 * @return void
	 */
	public function importFloskelgruppen(): void
	{
		if (is_null($this->floskelgruppen)) {
			return;
		}

		$this->start('Floskelgruppen mit Floskeln');

		foreach($this->floskelgruppen as $row) {
			$floskelgruppe = Floskelgruppe::firstOrCreate(
				['kuerzel' => $row['kuerzel']],
				Arr::except($row, ['floskeln'])
			);

			if ($floskelgruppe->wasRecentlyCreated === false) {
				continue;
			}

			foreach ($row['floskeln'] as $floskel) {
				$floskel['fach_id'] = $floskel['fachID'];
				$floskel['jahrgang_id'] = $floskel['jahrgangID'];

				unset($floskel['fachID'], $floskel['jahrgangID']);

				$floskelgruppe->floskeln()->create($floskel);
			}
		}

		$this->stop();
	}

	/**
	 * Creates the Lerngruppe model. The model will not be updated with future requests.
	 * Related Lehrer models will be created only if the Lerngruppe model was recently created.
	 *
	 * A lerngruppe either belongs to a Klasse model or is a Kurs. Depending on the presence of kursartID.
	 *
	 * TODO: A question to the Customer was sent regarding the newly introduced `-1` value for kursartID
	 *
	 * @return void
	 */
	public function importLerngruppen(): void
	{
		if (is_null($this->lerngruppen)) {
			return;
		}

		$this->start('Lerngruppen');

		foreach ($this->lerngruppen as $row) {
			$row['fach_id'] = $row['fachID'];

			if (in_array($row['kursartID'], [null, -1])) { // TODO: Check the `-1`
				$row['klasse_id'] = $row['kID'];
				unset($row['kursartID']);
			}

			unset($row['fachID']);

			$lerngruppe = Lerngruppe::firstOrCreate(
				['id' => $row['id']],
				Arr::except($row, ['lehrerID'])
			);

			if ($lerngruppe->wasRecentlyCreated === true) {
				$lerngruppe->lehrer()->attach($row['lehrerID']);
			}
		}

		$this->stop();
	}

	public function importTeilleistungsarten(): void
	{
		if (is_null($this->teilleistungsarten)) {
			return;
		}

		$this->start('Teilleistungsarten');

		foreach ($this->teilleistungsarten as $row) {
			Teilleistungsart::updateOrCreate(['id' => $row['id']], $row);
		}

		$this->stop();
	}

	/**
	 * Creates the Schueler model. The model will not be updated with future requests.
	 * Related Lernabschnitt model will be created only if the Lerngruppe model was recently created.
	 * TODO: ^^^ To be cleared with Customer
	 *
	 * @return void
	 */
	public function importSchueler(): void
	{
		if (is_null($this->schueler)) {
			return;
		}

		$this->start(text: 'Schüler mit Leistungsdaten und Lernabschnitte');

		foreach ($this->schueler as $row) {
			$row['jahrgang_id'] = $row['jahrgangID'];
			$row['klasse_id'] = $row['klasseID'];
			$row['geschlecht'] = $this->gender(data: $row, allowed: Schueler::GENDERS);

			unset($row['klasseID'], $row['jahrgangID']);

			// TODO: Temporary unset. TO BE CLEARED
			unset($row['sprachenfolge'], $row['zp10'], $row['bkabschluss']);

			$schueler = Schueler::firstOrCreate(
				['id' => $row['id']],
				Arr::except($row, ['bemerkungen', 'lernabschnitt', 'leistungsdaten'])
			);

			$this->importBemerkungen(schueler: $schueler, data: $row['bemerkungen']);
			$this->importLernabschnitte(schueler: $schueler, data: $row['lernabschnitt']);
			$this->importLeistungsdaten(schueler: $schueler, data: $row['leistungsdaten']);



//

//	TODO: Still missing in json
//			$this->importSprachenfolge($schueler, $row);
//			$this->importZp10($schueler, $row);
//			$this->importBkAbschluss($schueler, $row);
		}

		$this->stop();
	}

	private function importBemerkungen(Schueler $schueler, array $data): void
	{
		$bemerkung = Bemerkung::firstOrNew(['schueler_id' => $schueler->id]);

		$bemerkung->LELS = $data['LELS'];
		$bemerkung->schulformEmpf = $data['schulformEmpf'];
		$bemerkung->foerderbemerkungen = $data['foerderbemerkungen'];

		$this->updateByTimestamp($data, $bemerkung, 'ASV', 'tsASV');
		$this->updateByTimestamp($data, $bemerkung, 'AUE', 'tsAUE');
		$this->updateByTimestamp($data, $bemerkung, 'ZB', 'tsZB');
		$this->updateByTimestamp($data, $bemerkung, 'individuelleVersetzungsbemerkungen', 'tsIndividuelleVersetzungsbemerkungen');

		$bemerkung->save();
	}

	/**
	 * Creates the Lernabschnitt model.
	 * TODO: It is not yet clear if the model will not be updated with future requests or will.
	 * TODO: Still waiting for the information if the 4 fields will have the `id` instead of the `kuerzel`
	 *
	 * @param Schueler $schueler
	 * @param array $data
	 * @return void
	 */
	private function importLernabschnitte(Schueler $schueler, array $data): void
	{
		if ($data) { // TODO: To be updated if noteID is available // TODO: Idea to put into schueler

			$lernabschnitt = Lernabschnitt::firstOrNew(['id' => $data['id']], ['schueler_id' => $schueler->id]);

			$this->updateByTimestamp($data, $lernabschnitt, 'fehlstundenGesamt', 'tsFehlstundenGesamt');
			$this->updateByTimestamp($data, $lernabschnitt, 'fehlstundenUnentschuldigt', 'tsFehlstundenUnentschuldigt');

			$lernabschnitt->foerderschwerpunkt1 = $this->getValueFromArray(
				data: $data,
				column: 'foerderschwerpunkt1',
				collection: $this->existingFoerderschwerpunkte
			);

			$lernabschnitt->foerderschwerpunkt2 = $this->getValueFromArray(
				data: $data,
				column: 'foerderschwerpunkt2',
				collection: $this->existingFoerderschwerpunkte
			);

			$lernabschnitt->lernbereich1note = $this->getValueFromArray(
				data: $data,
				column: 'lernbereich1note',
				collection: $this->existingNoten
			);

			$lernabschnitt->lernbereich2note = $this->getValueFromArray(
				data: $data,
				column: 'lernbereich2note',
				collection: $this->existingNoten
			);

			$lernabschnitt->pruefungsordnung = $data['pruefungsordnung'] ?? 'Lorem ipsum'; // TODO: Check with customer
			$lernabschnitt->save();
		}
	}

	/**
	 * Creates the Leistung model. The model will be updated with future requests.
	 * The timestamp will be compared to check if the data was updated on the SVWS server.
	 * TODO: ^^^ Date missing. To be cleared which timestamp for which columns should be valid.
	 *
	 * @param Schueler $schueler
	 * @param array $data
	 * @return void
	 */
	private function importLeistungsdaten(Schueler $schueler, array $data): void
	{
		foreach ($data as $row) {
			$row['lerngruppe_id'] = $row['lerngruppenID'];
			unset($row['lerngruppenID']);

			$noteId = $this->getValueFromArray(data: $row, column: 'note', collection: $this->getExistingNoten());
			unset($row['note']);

			try {
				$leistung = Leistung::findOrFail($row['id']);

				$this->updateByTimestamp($row, $leistung, 'note_id', 'tsNote', $noteId);
				$this->updateByTimestamp($row, $leistung, 'fehlstundenGesamt', 'tsFehlstundenGesamt');
				$this->updateByTimestamp($row, $leistung, 'fehlstundenUnentschuldigt', 'tsFehlstundenUnentschuldigt');
				$this->updateByTimestamp($row, $leistung, 'fachbezogeneBemerkungen', 'tsFachbezogeneBemerkungen');
				$this->updateByTimestamp($row, $leistung, 'istGemahnt', 'tsIstGemahnt');

				$leistung->save();

			} catch (ModelNotFoundException $e) {
				report($e);

				$row['note_id'] = $noteId;

				$schueler->leistungen()->updateOrCreate(
					['id' => $row['id']],
					Arr::except($row, ['teilleistungen', 'lerngruppenID'])
				);
			}

			//TODO: To be cleared what the structure ist
			//foreach($leistungsdaten['teilleistungen'] as $teilleistung) {
			//	$leistung->teilleistungen()->create($teilleistung);
			//}
		}
	}


	/**
	 * Fill selected row with a value only if the timestamp is newer than the one stored in the database.
	 * If value parameter is provided, value will be used instead of the column value
	 *
	 * @param array $data
	 * @param Leistung|Lernabschnitt|Bemerkung $model
	 * @param string $column
	 * @param string $tsColumn
	 * @param int|null $value
	 * @return void
	 */
	private function updateByTimestamp(
		array $data,
		Leistung|Lernabschnitt|Bemerkung $model,
		string $column,
		string $tsColumn,
		int|null $value = null
	): void {
		$timestamp = Carbon::parse($data[$tsColumn]);

		if ($timestamp->gt($model->$tsColumn)) {
			$model->$column = $value ?? $data[$column];
			$model->$tsColumn = $timestamp->format('Y-m-d H:i:s.u');
		}
	}

	private function start(string $text): void
	{
		$this->microtime = microtime(true);
		echo "- $text: ";
	}

	private function stop(): void
	{
		$time = round(microtime(true) - $this->microtime, 2);
		echo "done in {$time}s." . (app()->runningInConsole() ? "\r\n" : "<br>");
	}

	private function getValueFromArray(array $data, string $column, array $collection): int|null
	{
		if ($data[$column] !== null && array_key_exists($data[$column], $collection)) {
			return $collection[$data[$column]];
		}

		return null;
	}

	private function trimWhitespaces(string $text): string
	{
		$text = preg_replace('/\s+/', ' ' , $text);
		return trim($text);
	}

	private function gender(array $data, array $allowed): string
	{
		if (array_key_exists('geschlecht', $data) && in_array($data['geschlecht'], $allowed)) {
			return $data['geschlecht'];
		}

		return 'x';
	}

	private function email(string|null $email): string
	{
		if ($email !== null && $email !== '') {
			return $email;
		}

		return sprintf('%s@%s', Str::random(32), Str::random(32));
	}








//    private function importDaten(): void
//    {
//        Daten::updateOrCreate(
//            ['user_id' => Lehrer::where('ext_id', $this->json['lehrerID'])->firstOrFail()->id],
//            [
//                'enmRevision' => $this->json['enmRevision'],
//                'schulnummer' => $this->json['schulnummer'],
//                'schuljahr' => $this->json['schuljahr'],
//                'anzahlAbschnitte' => $this->json['anzahlAbschnitte'],
//                'aktuellerAbschnitt' => $this->json['aktuellerAbschnitt'],
//                'publicKey' => $this->json['publicKey'],
//                'lehrerID' => $this->json['lehrerID'],
//                'fehlstundenEingabe' => $this->json['fehlstundenEingabe'],
//                'fehlstundenSIFachbezogen' => $this->json['fehlstundenSIFachbezogen'],
//                'fehlstundenSIIFachbezogen' => $this->json['fehlstundenSIIFachbezogen'],
//                'schulform' => $this->json['schulform'],
//                'mailadresse' => $this->json['mailadresse'],
//            ]
//        );
//    }










//    private function importSprachenfolge(Model|Schueler $model, array $data): void
//    {
//        foreach ($data['sprachenfolge'] as $row) {
//            $sprachenfolge = $model->sprachenfolgen()->make(Arr::except($row, ['fachID', 'sprache', 'fachKuerzel']));
//
//            $sprachenfolge->fach_id = Fach::firstOrCreate(
//                ['ext_id' => $row['fachID']],
//                ['fachKuerzel' => $row['sprache'], 'kuerzelAnzeige' => $row['fachKuerzel']]
//            )->id;
//
//            $sprachenfolge->save();
//        }
//    }
//
//
//    public function importZp10(Model|Schueler $model, array $data): void
//    {
//        if (!$data['zp10']) {
//            return;
//        }
//
//        $row = $data['zp10'];
//
//        $zp10 = $model->zp10()->firstOrNew(['ext_id' => $row['id']], Arr::except($row, ['fachID']));
//        $zp10->fach_id = $this->getRelation($row, Fach::class, 'vornote');
//        $zp10->vornote = $this->getRelation($row, Note::class, 'vornote', 'kuerzel');
//        $zp10->noteSchriftlichePruefung = $this->getRelation($row, Note::class, 'noteSchriftlichePruefung', 'kuerzel');
//        $zp10->noteMuendlichePruefung = $this->getRelation($row, Note::class, 'noteMuendlichePruefung', 'kuerzel');
//        $zp10->abschlussnote = $this->getRelation($row, Note::class, 'abschlussnote', 'kuerzel');
//        $zp10->save();
//    }
//
//    public function importBkAbschluss(Model|Schueler $model, array $data): void
//    {
//        if (!$data['bkabschluss']) {
//            return;
//        }
//
//        $row = $data['bkabschluss'];
//        $excluded = ['notePraktischePruefung', 'noteKolloqium', 'noteFachpraxis', 'faecher'];
//
//        $bkAbschluss = $model->bkabschluss()->firstOrNew(['ext_id' => $row['id']], Arr::except($row, $excluded));
//        $bkAbschluss->notePraktischePruefung = $this->getRelation($row, Note::class, 'notePraktischePruefung', 'kuerzel');
//        $bkAbschluss->noteKolloqium = $this->getRelation($row, Note::class, 'noteKolloqium', 'kuerzel');
//        $bkAbschluss->noteFachpraxis = $this->getRelation($row, Note::class, 'noteFachpraxis', 'kuerzel');
//        $bkAbschluss->save();
//
//        $this->importBkAbschlussFach($bkAbschluss, $row['faecher']);
//    }
//
//    public function importBkAbschlussFach(Model|BKAbschluss $model, array $data): void
//    {
//        if (!$data['faecher']) {
//            return;
//        }
//
//        $row = $data['faecher'];
//
//        $bkFach = $model->bkFaecher()->make(Arr::except($data, ['fachID', 'lehrerID']));
//        $bkFach->fach_id = $this->getRelation($row, Fach::class, 'fachID');
//        $bkFach->lehrer_id = $this->getRelation($row, Lehrer::class, 'lehrerID');
//        $bkFach->vornote = $this->getRelation($row, Note::class, 'vornote', 'kuerzel');
//        $bkFach->noteSchriftlichePruefung = $this->getRelation($row, Note::class, 'noteSchriftlichePruefung', 'kuerzel');
//        $bkFach->noteMuendlichePruefung = $this->getRelation($row, Note::class, 'noteMuendlichePruefung', 'kuerzel');
//        $bkFach->noteBerufsabschluss = $this->getRelation($row, Note::class, 'noteBerufsabschluss', 'kuerzel');
//        $bkFach->abschlussnote = $this->getRelation($row, Note::class, 'abschlussnote', 'kuerzel');
//        $bkFach->save();
//    }




}

