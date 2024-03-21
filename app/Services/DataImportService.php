<?php

namespace App\Services;

use App\Models\{
    Bemerkung, Fach, Floskelgruppe, Foerderschwerpunkt, Jahrgang, Klasse, Leistung, Lernabschnitt, Lerngruppe, Note,
    Schueler, Teilleistungsart, User,
};
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Schema;

class DataImportService
{
    // TODO: TO be removed by karol
	private array $existingNoten = [];
    // TODO: TO be removed by karol
	private array $existingFoerderschwerpunkte = [];
    // TODO: TO be removed by karol
	private int $microtime;

    public function __construct(
		private array $data = [],
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

        $this->importLehrer();
        $this->importKlassen();
        $this->importNoten();
	}

    // TODO: TO be removed by karol
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

			if ($name == 'settings') {
				continue;
			}

			DB::table($name)->truncate();
		}

		Schema::enableForeignKeyConstraints();

		User::factory()->create(attributes: [
			'email' => 'user@user.com',
			'is_administrator' => true,
		]);

		$this->stop();
	}

	public function execute(array $data): void
	{
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

		$this->import();
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
     * Creates or updates the Lehrer model.
     *
     * @return void
     */
	public function importLehrer(): void
	{
        collect($this->data['lehrer'] ?? [])
            ->map(fn(array $row): array => [
                ...$row,
                'email' => $row['eMailDienstlich'] ?? null
            ])
            ->each(fn (array $row): User => User::updateOrCreate(
                ['ext_id' => $row['id']],
                Arr::only($row, ['kuerzel', 'vorname', 'nachname', 'geschlecht', 'email'])
            ));
	}

	/**
	 * Creates the Klasse model.
     * The model will not be updated with future requests.
	 * After creating the model, the Lehrer model relationships are created.
	 * The relationships are only being set once, and will not trigger at consecutive requests.
	 *
	 * @return void
	 */
	public function importKlassen(): void
	{
        // Get only the Klassenlehrer that exist
        $klassenlehrer = fn (array $row): array => User::query()
            ->whereIn('ext_id', (array) $row['klassenlehrer'])
            ->pluck('ext_id')
            ->toArray();

        collect($this->data['klassen'] ?? [])
            // Filter out Klassen without klassenlehrer assigned
            ->filter(fn (array $row): bool =>
                array_key_exists('klassenlehrer', $row) && count($row['klassenlehrer'])
            )
            ->each(fn (array $row): Klasse => tap(
                Klasse::firstOrCreate(['id' => $row['id']], $row),
                fn (Klasse $klasse): array =>
                    $klasse->wasRecentlyCreated && count($row['klassenlehrer'])
                        ? $klasse->klassenlehrer()->sync($klassenlehrer($row))
                        : []
                )
            );
	}

    /**
	 * Creates the Note model.
     * The model will not be updated with future requests.
	 * Resources with an negative id are filtered out. Relatable models are nullable.
	 *
	 * TODO: All Models are stored in an array to be called by ID in different resources.
	 *
	 * @return void
	 */
	public function importNoten(): void
    {
		collect($this->data['noten'] ?? [])
            // Remove notes with negative id
            ->filter(fn (array $row): bool => array_key_exists('id', $row) && is_int($row['id']) && $row['id'] >= 0)
            // Remove notes without kuerzel
            ->filter(fn (array $row): bool => array_key_exists('kuerzel', $row) && null !== $row['kuerzel'])
            // Model can only be created
			->each(fn (array $row): Note => Note::firstOrCreate(['id' => $row['id']], $row));

		$this->existingNoten = $this->getExistingNoten(); // TODO: To be removed when IMPORT_LERNABSCHNITTE IS FIXED
    }

	/**
	 * Creates the Foerderschwerpunkt model.
     * The model will not be updated with future requests.
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

		$this->existingFoerderschwerpunkte = $this->getExistingFoerderschwerpunkte(); // TODO: To be removed

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
	 * Creates the Jahrgang model.
     * The model will not be updated with future requests.
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
			$row['beschreibung'] = $this->trimWhitespaces($row['beschreibung']); // TODO: Check with customer if still needed
			Jahrgang::firstOrCreate(['id' => $row['id']], $row);
		}

		$this->stop();
	}

	/**
	 * Creates the Fach model.
     * The model will not be updated with future requests.
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
	 * Creates the Floskelgruppe model.
     * The model will not be updated with future requests.
	 * Related floskel will be created only if the Floskelgruppe model was recently created.
	 *
	 * @return void
	 */
	public function importFloskelgruppen(): void
	{
		if (is_null($this->floskelgruppen)) {
			return;
		}

		$this->start(text: 'Floskelgruppen mit Floskeln');

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
	 * Creates the Lerngruppe model.
     * The model will not be updated with future requests.
	 * Related Lehrer models will be created only if the Lerngruppe model was recently created.
	 * A lerngruppe either belongs to a Klasse model or is a Kurs. Depending on the presence of kursartID.
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

			if (is_null($row['kursartID'])) {
				$row['klasse_id'] = $row['kID'];
				unset($row['kursartID']);
			}

			unset($row['fachID']);

			$lerngruppe = Lerngruppe::firstOrCreate(['id' => $row['id']], Arr::except($row, ['lehrerID']));

			if ($lerngruppe->wasRecentlyCreated === true) {
				$lerngruppe->lehrer()->attach($row['lehrerID']);
			}
		}

		$this->stop();
	}

    /**
     * Creates the Teilleistungen model.
     *
     * @return void
     */
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

		$this->start('Schüler mit Leistungsdaten und Lernabschnitte');

		foreach ($this->schueler as $row) {
			$row['jahrgang_id'] = $row['jahrgangID'];
			$row['klasse_id'] = $row['klasseID'];
			$row['geschlecht'] = $this->gender($row, Schueler::GENDERS);

			unset($row['klasseID'], $row['jahrgangID']);

			// TODO: Temporary unset. TO BE CLEARED
			unset($row['sprachenfolge'], $row['zp10'], $row['bkabschluss']);

			$schueler = Schueler::firstOrCreate(
				['id' => $row['id']],
				Arr::except($row, ['bemerkungen', 'lernabschnitt', 'leistungsdaten'])
			);

			$this->importBemerkungen($schueler, $row['bemerkungen']);
			$this->importLernabschnitte($schueler, $row['lernabschnitt']);
			$this->importLeistungsdaten($schueler, $row['leistungsdaten']);



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

		$this->updateWhenRecent($data, $bemerkung, 'ASV', 'tsASV');
		$this->updateWhenRecent($data, $bemerkung, 'AUE', 'tsAUE');
		$this->updateWhenRecent($data, $bemerkung, 'ZB', 'tsZB');
		$this->updateWhenRecent(
			$data,
			$bemerkung,
			'individuelleVersetzungsbemerkungen',
			'tsIndividuelleVersetzungsbemerkungen'
		);

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

			$lernabschnitt = Lernabschnitt::firstOrNew(['id' => $data['id']]);

			$lernabschnitt->schueler_id = $schueler->id;

			$this->updateWhenRecent($data, $lernabschnitt, 'fehlstundenGesamt', 'tsFehlstundenGesamt');

			$this->updateWhenRecent(
                $data,
                $lernabschnitt,
                'fehlstundenGesamtUnentschuldigt',
                'tsFehlstundenGesamtUnentschuldigt'
			);

			$lernabschnitt->foerderschwerpunkt1 = $this->getValueFromArray(
				$data, 'foerderschwerpunkt1', $this->existingFoerderschwerpunkte
			);

			$lernabschnitt->foerderschwerpunkt2 = $this->getValueFromArray(
				$data, 'foerderschwerpunkt2', $this->existingFoerderschwerpunkte
			);

			$lernabschnitt->lernbereich1note = $this->getValueFromArray(
				$data, 'lernbereich1note', $this->existingNoten
			);

			$lernabschnitt->lernbereich2note = $this->getValueFromArray(
				$data, 'lernbereich2note', $this->existingNoten
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

				$this->updateWhenRecent($row, $leistung, 'note_id', 'tsNote', $noteId);
				$this->updateWhenRecent($row, $leistung, 'fehlstundenFach', 'tsFehlstundenFach');
				$this->updateWhenRecent(
                    $row, $leistung, 'fehlstundenUnentschuldigtFach', 'tsFehlstundenUnentschuldigt'
                );
				$this->updateWhenRecent(
                    $row, $leistung, 'fachbezogeneBemerkungen', 'tsFehlstundenUnentschuldigtFach'
                );
				$this->updateWhenRecent($row, $leistung, 'istGemahnt', 'tsIstGemahnt');

				$leistung->save();

			} catch (ModelNotFoundException $e) {
				report($e);

				$row['note_id'] = $noteId;

				$schueler->leistungen()->updateOrCreate(
					['id' => $row['id']], Arr::except($row, ['teilleistungen', 'lerngruppenID'])
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
	private function updateWhenRecent(
		array $data,
		Leistung|Lernabschnitt|Bemerkung $model,
		string $column,
		string $tsColumn,
		int|null $value = null
	): void {
		$timestamp = Carbon::parse( $data[$tsColumn]);

		if ($timestamp->gt($model->$tsColumn)) {
			$model->$column = $value ?? $data[$column];
			$model->$tsColumn = $timestamp->format('Y-m-d H:i:s.u');
		}
	}

    /**
     * Start timer
     *
     * @param string $text
     * @return void
     */
    private function start(string $text): void
	{
		$this->microtime = microtime(true);
		echo "- $text: ";
	}

    /**
     * Stop timer
     *
     * @return void
     */
    private function stop(): void
	{
		$time = round(microtime(true) - $this->microtime, 2);
		echo "done in {$time}s." . (app()->runningInConsole() ? "\r\n" : "<br>");
	}

    /**
     * Get value from array
     *
     * @param array $data
     * @param string $column
     * @param array $collection
     * @return int|null
     */
    private function getValueFromArray(array $data, string $column, array $collection): int|null
	{
		if ($data[$column] !== null && array_key_exists($data[$column], $collection)) {
			return $collection[$data[$column]];
		}

		return null;
	}

    /**
     * Trim whitespace
     *
     * @param string $text
     * @return string
     */
    private function trimWhitespaces(string $text): string
	{
		return trim(preg_replace('/\s+/', ' ' , $text));
	}

    /**
     * Get gender
     *
     * @param array $data
     * @param array $allowed
     * @return string
     */
    private function gender(array $data, array $allowed): string
	{
		if (array_key_exists('geschlecht', $data) && in_array($data['geschlecht'], $allowed)) {
			return $data['geschlecht'];
		}

		return User::FALLBACK_GENDER;
	}

    /**
     * Format email
     * If none provided, generate a random one
     *
     * @param string|null $email
     * @return string
     */
    private function formatEmail(string|null $email): string
	{
		$validator = Validator::make(
            ['email' => $email],
            ['email' => ['required', 'email:rfc,dns']]
        );

		if ($validator->valid()) {
			return strtolower($email);
		}

		return sprintf('%s@%s', Str::random(32), Str::random(32));
	}
}

