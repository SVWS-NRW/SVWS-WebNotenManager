<?php

namespace App\Services;

use App\Models\{
    Bemerkung, Fach, Floskelgruppe, Floskel, Foerderschwerpunkt, Jahrgang, Klasse, Leistung, Lernabschnitt, Lerngruppe,
    Note, Schueler, Teilleistungsart, User,
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Collection, ModelNotFoundException};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\{Arr, Str};
use Illuminate\Support\Facades\Validator;
use Schema;

class DataImportService
{
    private array $status = [
        'errors' => [],
        'success' => [],
    ];

    // TODO: TO be removed by karol
    private array $existingNoten = [];
    // TODO: TO be removed by karol
    private array $existingFoerderschwerpunkte = [];

    /**
     * Class constructor
     *
     * @param array $data
     */
    public function __construct(private array $data = [])
    {
        $this->execute();
    }

    /**
     * Import execution
     *
     * @return JsonResponse
     */
    public function execute(): JsonResponse
    {
        $this->importLehrer();
        $this->importJahrgaenge();
        $this->importKlassen();
        $this->importNoten();
        $this->importFoerderschwerpunkte();
        $this->importFaecher();
        $this->importFloskelgruppen();
        $this->importLerngruppen();
        $this->importSchueler();
        $this->importBemerkungen();

        return response()->json($this->status);
    }

    /**
     * Creates or updates the Lehrer model.
     * The id is stored under "ext_id" since we have system users as well.
     *
     * @return void
     */
    public function importLehrer(): void
    {
        if ($this->hasInvalidKey($this->data, 'lehrer', 'global')) {
            return;
        }

        foreach($this->data['lehrer'] as $row) {
            // Remaps the email and falls back to null
            $email = $row['email'] = $row['eMailDienstlich'] ?? null;

            // Sets notification that the email is not valid.
            // A temporary email will be set in UserObserver
            if (!$email || $email == '' || is_null($email)) {
                $this->setStatus($row, 'lehrer', '"eMailDienstlich" ist leer oder ungueltig');
            }

            $user = User::updateOrCreate(
                ['ext_id' => $row['id']],
                Arr::only($row, ['kuerzel', 'vorname', 'nachname', 'geschlecht', 'email']),
            );

            $status = $user->wasRecentlyCreated ? 'Erfolgreich angelegt' : 'Erfolgreich aktualisiert';
            $this->setStatus($user->ext_id, 'lehrer', $status, 'success');
        }
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
        if ($this->hasInvalidKey($this->data, 'klassen', 'global')) {
            return;
        }

        // Existing "Klassenlehrer"
        $klassenlehrer = fn (array $row): array => User::query()
            ->whereIn('ext_id', (array) $row['klassenlehrer'])
            ->pluck('ext_id')
            ->toArray();

        foreach($this->data['klassen'] as $row) {
            $klassen = Klasse::all();

            if ($this->hasInvalidKey($row, 'klassenlehrer', 'klassen')) {
                continue;
            }

            if (count($row['klassenlehrer']) <= 0) {
                $this->setStatus($row, 'klassen', '"klassenlehrer" ist leer');
                continue;
            }

            if ($this->hasInvalidId($row, 'klassen', $klassen)) {
                continue;
            }

            if ($this->hasInvalidKuerzel($row, 'klassen', $klassen)) {
                continue;
            }

            if ($this->hasInvalidSortierung($row, 'klassen', $klassen)) {
                continue;
            }

            if ($this->hasInvalidKey($row, 'idJahrgang', 'klassen')) {
                continue;
            }

            // Check if the "Jahrgang" already exists in the database
            if (Jahrgang::where(['id' => $row['idJahrgang']])->doesntExist()) {
                $this->setStatus($row, 'klassen', 'Jahrgang mit id ' . $row['idJahrgang'] . ' existiert nicht.');
                continue;
            }

            $klasse = Klasse::create($row);
            $klasse->klassenlehrer()->sync($klassenlehrer($row));

            $this->setSuccessStatus($row, 'klassen', 'Erfolgreich angeleg, klassenlehrer wurden zugewiesen');
        }
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
        if ($this->hasInvalidKey($this->data, 'noten', 'global')) {
            return;
        }

        foreach($this->data['noten'] as $row) {
            $noten = Note::all();

            if ($this->hasInvalidId($row, 'noten', $noten)) {
                continue;
            }

            if ($this->hasInvalidKuerzel($row, 'noten', $noten)) {
                continue;
            }

            Note::create($row);
            $this->setStatus($row, 'noten', 'Erfolgreich angelegt', 'success');
        }

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
        if ($this->hasInvalidKey($this->data, 'foerderschwerpunkte', 'global')) {
            return;
        }

        foreach($this->data['foerderschwerpunkte'] as $row) {
            $foerderschwerpunkte = Foerderschwerpunkt::all();

            if ($this->hasInvalidId($row, 'foerderschwerpunkte', $foerderschwerpunkte)) {
                continue;
            }

            if ($this->hasInvalidKuerzel($row, 'foerderschwerpunkte', $foerderschwerpunkte)) {
                continue;
            }

            if ($this->hasInvalidKey($row, 'beschreibung', 'foerderschwerpunkte')) {
                continue;
            }

            Foerderschwerpunkt::create($row);
            $this->setStatus($row, 'foerderschwerpunkte', 'Erfolgreich angelegt', 'success');
        }

        $this->existingFoerderschwerpunkte = $this->getExistingFoerderschwerpunkte(); // TODO: To be removed
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
        if ($this->hasInvalidKey($this->data, 'jahrgaenge', 'global')) {
            return;
        }

        foreach($this->data['jahrgaenge'] as $row) {
            $jahrgaenge = Jahrgang::all();

            if ($this->hasInvalidId($row, 'jahrgaenge', $jahrgaenge)) {
                continue;
            }

            if ($this->hasInvalidKuerzel($row, 'jahrgaenge', $jahrgaenge)) {
                continue;
            }

            if ($this->hasInvalidSortierung($row, 'jahrgaenge', $jahrgaenge)) {
                continue;
            }

            if ($this->hasInvalidKey($row, 'kuerzelAnzeige', 'jahrgaenge')) {
                continue;
            }

            if ($this->hasInvalidKey($row, 'stufe', 'jahrgaenge')) {
                continue;
            }

            if ($this->hasInvalidKey($row, 'beschreibung', 'jahrgaenge')) {
                continue;
            }

            // In past there was an issue with additional whitespace. This function takes care of it and notifies about.
            $beschreibung = $row['beschreibung'];
            if (substr($beschreibung, 0, 1) === " " || $beschreibung !== ltrim($beschreibung)) {
                $row['beschreibung'] = $this->trimWhitespaces($row['beschreibung']);
                $this->setStatus($row, 'jahrgaenge', 'Potentieles whitespace Problem in Beschreibung');
            }

            Jahrgang::create($row);
            $this->setStatus($row, 'jahrgaenge', 'Erfolgreich angelegt', 'success');
        }
    }

    /**
     * Creates the Fach model.
     * The model will not be updated with future requests.
     *
     * @return void
     */
    public function importFaecher(): void
    {
        if ($this->hasInvalidKey($this->data, 'faecher', 'global')) {
            return;
        }

        foreach($this->data['faecher'] as $row) {
            $faecher = Fach::all();

            if ($this->hasInvalidId($row, 'faecher', $faecher)) {
                continue;
            }

            if ($this->hasInvalidKuerzel($row, 'faecher', $faecher)) {
                continue;
            }

            if ($this->hasInvalidSortierung($row, 'faecher', $faecher)) {
                continue;
            }

            if ($this->hasInvalidKey($row, 'istFremdsprache', 'faecher')) {
                continue;
            }

            Fach::create($row);
            $this->setStatus($row, 'faecher', 'Erfolgreich angelegt', 'success');
        }
    }

    /**
     * Creates the Floskelgruppe model.
     * The model will not be updated with future requests.
     *
     * @return void
     */
    public function importFloskelgruppen(): void
    {
        if ($this->hasInvalidKey($this->data, 'floskelgruppen', 'global')) {
            return;
        }

        foreach($this->data['floskelgruppen'] as $row) {
            $floskelgruppen = Floskelgruppe::all();

            if ($this->hasInvalidKuerzel($row, 'floskelgruppe', $floskelgruppen)) {
                continue;
            }

            if ($this->hasInvalidKey($row, 'bezeichnung', 'floskelgruppe')) {
                continue;
            }

            if ($this->hasInvalidKey($row, 'hauptgruppe', 'floskelgruppe')) {
                continue;
            }

            $floskelgruppe = Floskelgruppe::create(Arr::except($row, ['floskeln']));

            if ($this->hasInvalidKey($row, 'floskeln', 'floskelgruppe')) {
                continue;
            }

            if (count($row['floskeln']) <= 0) {
                $this->setStatus($row, 'floskelgruppen', 'Keine Floskeln vorhanden.');
                continue;
            }

            $this->setStatus($row, 'floskelgruppe', 'Erfolgreich angelegt', 'success');

            $this->importFloskeln($floskelgruppe, $row['floskeln']);
        }
    }

    /**
     * Import floskeln into floskelgruppen
     *
     * @param Floskelgruppe $floskelgruppe
     * @param array $floskeln
     * @return void
     */
    private function importFloskeln(Floskelgruppe $floskelgruppe, array $floskeln): void
    {
        foreach($floskeln as $row) {
            $floskeln = Floskel::all();

            if ($this->hasInvalidKuerzel($row, 'floskel', $floskeln)) {
                continue;
            }

            if ($this->hasInvalidKey($row, 'text', 'floskel')) {
                continue;
            }

            if (!array_key_exists('fachID', $row)) {
                $this->setStatus($row, 'floskel', "'jahrgangID' nicht vorhanden");
                continue;
            }

            if (!is_null($row['fachID']) && Fach::where(['id' => $row['fachID']])->doesntExist()) {
                $this->setStatus($row, 'floskel', 'Fach mit id ' . $row['fachID'] . ' existiert nicht.');
                continue;
            }

            if (!array_key_exists('jahrgangID', $row)) {
                $this->setStatus($row, 'floskel', "'jahrgangID' nicht vorhanden");
                continue;
            }

            if (!is_null($row['jahrgangID']) && Jahrgang::where(['id' => $row['jahrgangID']])->doesntExist()) {
                $this->setStatus($row, 'floskel', 'Jahrgang mit id ' . $row['jahrgangID'] . ' existiert nicht.');
                continue;
            }

            $row['fach_id'] = $row['fachID'];
            $row['jahrgang_id'] = $row['jahrgangID'];
            unset($row['fachID'], $row['jahrgangID']);

            $floskelgruppe->floskeln()->create($row);
            $this->setStatus($row, 'floskel', 'Erfolgreich angelegt', 'success');
        }
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
        if ($this->hasInvalidKey($this->data, 'lerngruppen', 'global')) {
            return;
        }

        foreach ($this->data['lerngruppen'] as $row) {
            $lerngruppen = Lerngruppe::all();

            // Check if following fields are present and properly formatted.
            if ($this->hasInvalidId($row, 'lerngruppe', $lerngruppen)) {
                continue;
            }
            if ($this->hasInvalidKey($row, 'bezeichnung', 'lerngruppe')) {
                continue;
            }
            if ($this->hasInvalidInt($row, 'wochenstunden', 'lerngruppe')) {
                continue;
            }

            // Check if "fachID" is present and properly formatted.
            if ($this->hasInvalidKey($row, 'fachID', 'lerngruppe')) {
                continue;
            }
            // Check if corresponding "Fach exists"
            if (Fach::where(['id' => $row['fachID']])->doesntExist()) {
                $this->setStatus($row, 'lerngruppe', 'Fach mit id ' . $row['fachID'] . ' existiert nicht.');
                continue;
            }
            // Remap the keys and unset unused valued
            $row['fach_id'] = $row['fachID'];
            unset($row['fachID']);

            // Check if "kID" is present and properly formatted.
            if ($this->hasInvalidInt($row, 'kID', 'lerngruppe')) {
                continue;
            }
            // Check if "kursartID" is set to NULL which indicates this "Lerngruppe" is assigned to a class.
            // Therefor we dont need the kursartID anymore.
            if (is_null($row['kursartID'])) {
                $row['klasse_id'] = $row['kID'];
                unset($row['kursartID']);

                // Check if "Klasse" exists
                if (Klasse::where(['id' => $row['kID']])->doesntExist()) {
                    $this->setStatus($row, 'lerngruppe', 'Klasse mit id ' . $row['kID'] . ' existiert nicht.');
                    continue;
                }
            }

            // Check if "lehrerID" is present and is properly formatted
            if ($this->hasInvalidKey($row, 'lehrerID', 'lerngruppe')) {
                continue;
            }
            // Filter out non integer values
            $lehrerIds = array_filter($row['lehrerID'], fn (int|string|null $value): bool => is_int($value));
            // Check if there are any IDs in the element
            if (0 === count($lehrerIds)) {
                $this->setStatus($row, 'lerngruppen', '"lehrerID" ist leer.');
                continue;
            }
            // Check if all users with corresponding lehrerID exists. If not, log and continue.
            if (User::whereIn('ext_id', $lehrerIds)->count() !== count($lehrerIds)) {
                $this->setStatus($row, 'lerngruppen', 'Nicht alle Lehrer existieren bereits.');
                continue;
            }

            $lerngruppe = Lerngruppe::create(Arr::except($row, ['lehrerID']));
            $lerngruppe->lehrer()->attach($row['lehrerID']);
        }
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
        if ($this->hasInvalidKey($this->data, 'schueler', 'global')) {
            return;
        }

        foreach ($this->data['schueler'] as $row) {
            $schueler = Schueler::all();

            // Check if following fields are present and properly formatted.
            if ($this->hasInvalidId($row, 'schueler', $schueler)) {
                continue;
            }

            // Check if "jahrgangID" is present and properly formatted.
            if ($this->hasInvalidInt($row, 'jahrgangID', 'schueler')) {
                continue;
            }
            // Check if corresponding "Jahrgang" exists
            if (Jahrgang::where(['id' => $row['jahrgangID']])->doesntExist()) {
                $this->setStatus($row, 'schueler', 'Jahrgang mit id ' . $row['jahrgangID'] . ' existiert nicht.');
                continue;
            }
            // Remap the keys and unset unused valued
            $row['jahrgang_id'] = $row['jahrgangID'];
            unset($row['jahrgangID']);

            // Check if "klasseID" is present and properly formatted.
            if ($this->hasInvalidInt($row, 'klasseID', 'schueler')) {
                continue;
            }
            // Check if corresponding "Klasse" exists
            if (Klasse::where(['id' => $row['klasseID']])->doesntExist()) {
                $this->setStatus($row, 'schueler', 'Klasse mit id ' . $row['klasseID'] . ' existiert nicht.');
                continue;
            }
            // Remap the keys and unset unused valued
            $row['klasse_id'] = $row['klasseID'];
            unset($row['klasseID']);

            // Check if "geschlecht" is present and is properly formatted
            if ($this->hasInvalidKey($row, 'geschlecht', 'lerngruppe')) {
                continue;
            }
            $row['geschlecht'] = $this->gender($row, Schueler::GENDERS);

            // TODO: Temporary unset. TO BE CLEARED
            unset($row['sprachenfolge'], $row['zp10'], $row['bkabschluss']);

            $schueler = Schueler::create(
                Arr::except($row, ['bemerkungen', 'lernabschnitt', 'leistungsdaten'])
            );

            // $this->importBemerkungen($schueler, $row['bemerkungen']);
            //$this->importLernabschnitte($schueler, $row['lernabschnitt']);
            //$this->importLeistungsdaten($schueler, $row['leistungsdaten']);
        }
    }

    /**
     * Import "Schueler" "Bemerkungen"
     *
     * @param Schueler $schueler
     * @param array $data
     * @return void
     */
    public function importBemerkungen(): void
    {
        if ($this->hasInvalidKey($this->data, 'schueler', 'global')) {
            return;
        }

        foreach ($this->data['schueler'] as $row) {
            // Check if "Schueler" exists
            $schueler = Schueler::find($row['id']);
            if (!$schueler) {
                $this->setStatus($row, 'bemerkungen', 'Schuler mit id ' . $row['id'] . ' existiert nicht.');
                continue;

            }
            // Check if "Bemerkungen" are present and formatted
            if ($this->hasInvalidKey($row, 'bemerkungen', 'bemerkungen')) {
                continue;
            }

            // Check if "Bemerkung" already exists. If not, create a new one.
            $bemerkung = Bemerkung::firstOrNew(['schueler_id' => $schueler->id]);

            $data = $row['bemerkungen'];
            $bemerkung->LELS = $data['LELS'];
            $bemerkung->schulformEmpf = $data['schulformEmpf'];
            $bemerkung->foerderbemerkungen = $data['foerderbemerkungen'];

            // Update the following columns only if the value is present and timestamp is latter.
            if ($this->hasMissingKey($data, 'ASV', 'bemerkungen')) {
                continue;
            }
            if ($this->hasInvalidKey($data, 'tsASV', 'bemerkungen')) {
                continue;
            }
            $this->updateWhenRecent($data, $bemerkung, 'ASV', 'tsASV');

            if ($this->hasMissingKey($data, 'AUE', 'bemerkungen')) {
                continue;
            }
            if ($this->hasInvalidKey($data, 'tsAUE', 'bemerkungen')) {
                continue;
            }
            $this->updateWhenRecent($data, $bemerkung, 'AUE', 'tsAUE');

            if ($this->hasMissingKey($data, 'ZB', 'bemerkungen')) {
                continue;
            }
            if ($this->hasInvalidKey($data, 'tsZB', 'bemerkungen')) {
                continue;
            }
            $this->updateWhenRecent($data, $bemerkung, 'ZB', 'tsZB');

            if ($this->hasMissingKey($data, 'individuelleVersetzungsbemerkungen', 'bemerkungen')) {
                continue;
            }
            if ($this->hasInvalidKey($data, 'tsIndividuelleVersetzungsbemerkungen', 'bemerkungen')) {
                continue;
            }
            $this->updateWhenRecent(
                $data,
                $bemerkung,
                'individuelleVersetzungsbemerkungen',
                'tsIndividuelleVersetzungsbemerkungen'
            );

            $bemerkung->save();
            $this->setStatus($row, 'bemerkung', 'Erfolgreich gespeichert', 'success');
        }
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
                $data,
                'foerderschwerpunkt1',
                $this->existingFoerderschwerpunkte
            );

            $lernabschnitt->foerderschwerpunkt2 = $this->getValueFromArray(
                $data,
                'foerderschwerpunkt2',
                $this->existingFoerderschwerpunkte
            );

            $lernabschnitt->lernbereich1note = $this->getValueFromArray(
                $data,
                'lernbereich1note',
                $this->existingNoten
            );

            $lernabschnitt->lernbereich2note = $this->getValueFromArray(
                $data,
                'lernbereich2note',
                $this->existingNoten
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
                    $row,
                    $leistung,
                    'fehlstundenUnentschuldigtFach',
                    'tsFehlstundenUnentschuldigt'
                );
                $this->updateWhenRecent(
                    $row,
                    $leistung,
                    'fachbezogeneBemerkungen',
                    'tsFehlstundenUnentschuldigtFach'
                );
                $this->updateWhenRecent($row, $leistung, 'istGemahnt', 'tsIstGemahnt');

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
    private function updateWhenRecent(
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

            $this->setStatus(
                $data,
                'timestamp',
                'Datensatz wurde aktualisiert da es einen neuren Timestamp hat',
                'success',
            );
        }
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
        return trim(preg_replace('/\s+/', ' ', $text));
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

    private function setSuccessStatus(array|string|int $data, string $id, string $message): void
    {
        $this->setStatus($data, $id, $message, 'success');
    }

    private function setStatus(array|string|int $data, string $id, string $message, string $type = 'errors'): void
    {
        $this->status[$type][$id][] = [
            'message' => $message,
            'data' => $data,
        ];
    }

    // TO BE REMOVED, NEEDS SVWS SERVER UPDATES
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
     * Checks if given element has invalid id
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @return bool
     */
    private function hasInvalidId(array $row, string $context, Collection $existing): bool
    {
        if (!array_key_exists('id', $row)) {
            $this->setStatus($row, $context, '"id" nicht vorhanden');
            return true;
        }

        if (!is_int($row['id'])) {
            $this->setStatus($row, $context, '"id" ist keine Zahl');
            return true;
        }

        if ($row['id'] < 0) {
            $this->setStatus($row, $context, '"id" ist negativ');
            return true;
        }

        if ($existing->contains($row['id'])) {
            $this->setStatus($row, $context, 'Ressource mit diesen "id" existiert bereits');
            return true;
        }

        return false;
    }


    /**
     * Checks if given element has invalid kuerzel
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @return bool
     */
    private function hasInvalidKuerzel(array $row, string $context, Collection $existing): bool
    {
        if (!array_key_exists('kuerzel', $row)) {
            $this->setStatus($row, $context, '"keurzel" nicht vorhanden');
            return true;
        }

        if (is_null($row['kuerzel'])) {
            $this->setStatus($row, $context, '"keurzel" ist leer');
            return true;
        }

        if ($existing->filter(fn (mixed $item): bool => $item['kuerzel'] ==  $row['kuerzel'])->count() > 0) {
            $this->setStatus($row, $context, 'Ressource mit diesen "kuerzel" existiert bereits');
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has invalid sortierung
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @return bool
     */
    private function hasInvalidSortierung(array $row, string $context, Collection $existing): bool
    {

        if (!array_key_exists('sortierung', $row)) {
            $this->setStatus($row, $context, '"sortierung" nicht vorhanden');
            return true;
        }

        if (is_null($row['sortierung'])) {
            $this->setStatus($row, $context, '"sortierung" ist leer');
            return true;
        }

        if (!is_int($row['sortierung'])) {
            $this->setStatus($row, $context, '"sortierung" ist keine Zahl');
            return true;
        }

        if ($existing->filter(fn (mixed $item): bool => $item['sortierung'] == $row['sortierung'])->count() > 0) {
            $this->setStatus($row, $context, 'Ressource mit diesen "sortierung" bereits existiert');
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has a key mising
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function hasMissingKey(array $row, string $key, string $context): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($row, $context, "{$key} nicht vorhanden");
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has invalid key
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function hasInvalidKey(array $row, string $key, string $context): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($row, $context, "{$key} nicht vorhanden");
            return true;
        }

        if (is_null($row[$key])) {
            $this->setStatus($row, $context, "{$key} ist leer");
            return true;
        }

        return false;
    }

    /**
     * Checks if given element has invalid integer
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function hasInvalidInt(array $row, string $key, string $context): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($row, $context, "{$key} nicht vorhanden");
            return true;
        }

        if (is_null($row[$key])) {
            $this->setStatus($row, $context, "{$key} ist leer");
            return true;
        }


        if (!is_int($row[$key])) {
            $this->setStatus($row, $context, "{$key} kein Zahl");
            return true;
        }

        if (0 > $row[$key]) {
            $this->setStatus($row, $context, "{$key} ist negativ");
            return true;
        }

        return false;
    }
}
