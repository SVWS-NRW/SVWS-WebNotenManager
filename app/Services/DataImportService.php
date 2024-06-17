<?php

namespace App\Services;

use App\Models\{
    Bemerkung, Fach, Floskelgruppe, Floskel, Foerderschwerpunkt, Jahrgang, Klasse, Leistung, Lernabschnitt, Lerngruppe,
    Note, Schueler, User,
};
use App\Models\Teilleistungsart;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\{Arr, Str};
use Illuminate\Support\Facades\Validator;

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
    }

    /**
     * Import execution
     *
     * @return JsonResponse
     */
    public function execute(): static
    {
        $this->importLehrer();
        $this->importJahrgaenge();
        $this->importKlassen();
        $this->importNoten();
        $this->importFoerderschwerpunkte();
        $this->importFaecher();
        $this->importLerngruppen();
        $this->importSchueler();
        $this->importLeistungsdaten();
        $this->importBemerkungen();
        $this->importFloskelgruppen();
        $this->importTeilleistungsarten();

        return $this;
    }

    /**
     * Retrieve the response
     *
     * @return JsonResponse
     */
    public function response(): JsonResponse
    {
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
        if ($this->keyMissingOrEmpty($this->data, 'lehrer', 'global')) {
            return;
        }

        // Check if email is correct. If not, fallback to temporary random email that will be updated in the future.
        $checkEmail = function (array $row): array {
            // Maps the email and falls back to null
            $email = $row['email'] = $row['eMailDienstlich'] ?? null;

            // Sets notification that the email is not valid. A temporary email will be assigned in UserObserver
            if (!$email || $email == '' || is_null($email)) {
                $this->setStatus('lehrer', '"eMailDienstlich" ist leer oder ungueltig', $row['id']);
            }

            unset($row['eMailDienstlich']);

            return $row;
        };

        collect($this->data['lehrer'])
            ->map($checkEmail)
            ->each(fn (array $array): User => User::updateOrCreate(['ext_id' => $array['id']], $array));
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
        if ($this->keyMissingOrEmpty($this->data, 'jahrgaenge', 'global')) {
            return;
        }

        $jahrgaenge = Jahrgang::all();

        $trimBeschreibung = function (array $array) {
            // In past there was an issue with additional whitespace. This function takes care of it and notifies about.
            $beschreibung = $array['beschreibung'];
            if (preg_match('/\s{2,}/', $beschreibung)) {
                $this->setStatus('jahrgaenge', 'Potentieles whitespace Problem in Beschreibung', $array['beschreibung']);
                $array['beschreibung'] = $this->trimWhitespaces($array['beschreibung']);
            }

            return $array;
        };

        collect($this->data['jahrgaenge'])
            ->filter(fn (array $array): bool => $this->hasValidId($array, 'jahrgaenge', $jahrgaenge))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'jahrgaenge', $jahrgaenge, 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'jahrgaenge', $jahrgaenge, 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'kuerzelAnzeige'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'stufe'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'beschreibung'))
            ->map($trimBeschreibung)
            ->each(fn (array $array) =>  Jahrgang::create($array));
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
        if ($this->keyMissingOrEmpty($this->data, 'klassen', 'global')) {
            return;
        }

        // Existing "Klassenlehrer"
        $klassenlehrer = fn (array $row): array => User::query()
            ->whereIn('ext_id', (array) $row['klassenlehrer'])
            ->pluck('ext_id')
            ->toArray();

        $klassen = Klasse::all();

        collect($this->data['klassen'])
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'klassen', 'klassenlehrer'))
            ->filter(function (array $array): bool {
                if (count($array['klassenlehrer']) <= 0) {
                    $this->setStatus('klassen', '"klassenlehrer" ist leer', $array['klassenlehrer']);
                    return false;
                }

                return true;
            })
            ->filter(fn (array $array): bool => $this->hasValidId($array, 'klassen', $klassen))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'klassen', 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'klassen', $klassen, 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'klassen', 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'klassen', $klassen, 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'klassen', 'idJahrgang'))
            ->filter(function (array $array): bool {
                if (Jahrgang::where(['id' => $array['idJahrgang']])->doesntExist()) {
                    $this->setStatus('klassen', 'Jahrgang mit id ' . $array['idJahrgang'] . ' existiert nicht.');
                    return false;
                }

                return true;
            })
        ->each(function (array $array) use ($klassenlehrer): void {
            $klasse = Klasse::create(Arr::except($array, 'klassenlehrer'));
            $klasse->klassenlehrer()->sync($klassenlehrer($array));
        });
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
        if ($this->keyMissingOrEmpty($this->data, 'noten', 'global')) {
            return;
        }

        $noten = Note::all();

        collect($this->data['noten'])
            ->filter(fn (array $array): bool => $this->hasValidId($array, 'noten', $noten))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'noten', 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'noten', $noten, 'kuerzel'))
            ->each(fn (array $array): Note => Note::create($array));

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
        if ($this->keyMissingOrEmpty($this->data, 'foerderschwerpunkte', 'global')) {
            return;
        }

        $foerderschwerpunkte = Foerderschwerpunkt::all();

        collect($this->data['foerderschwerpunkte'])
            ->filter(fn (array $array): bool => $this->hasValidId($array, 'foerderschwerpunkte', $foerderschwerpunkte))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'foerderschwerpunkte', 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'foerderschwerpunkte', $foerderschwerpunkte, 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'foerderschwerpunkte', 'beschreibung'))
            ->each(fn (array $array): Foerderschwerpunkt => Foerderschwerpunkt::create($array));

        $this->existingFoerderschwerpunkte = $this->getExistingFoerderschwerpunkte(); // TODO: To be removed
    }

    /**
     * Creates the Fach model.
     * The model will not be updated with future requests.
     *
     * @return void
     */
    public function importFaecher(): void
    {
        if ($this->keyMissingOrEmpty($this->data, 'faecher', 'global')) {
            return;
        }

        $faecher = Fach::all();

        collect($this->data['faecher'])
            ->filter(fn (array $array): bool => $this->hasValidId($array, 'faecher', $faecher))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'faecher', 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'faecher', $faecher, 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'faecher', 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'faecher', $faecher, 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'faecher', 'istFremdsprache'))
            ->each(fn (array $array): Fach => Fach::create($array));
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
        if ($this->keyMissingOrEmpty($this->data, 'lerngruppen', 'global')) {
            return;
        }

        $lerngruppen = Lerngruppe::all();

        collect($this->data['lerngruppen'])
            ->filter(fn (array $array): bool => $this->hasValidId($array, 'lerngruppen', $lerngruppen))
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'lerngruppen', 'kID'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'lerngruppen', 'bezeichnung'))
            // Check if "kID" is present and properly formatted.
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'lerngruppen', 'wochenstunden'))
            // Check if relate "Fach exists"
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'lerngruppen', 'fachID'))
            ->filter(function (array $array): bool {
                if (Fach::where(['id' => $array['fachID']])->doesntExist()) {
                    $this->setStatus('lerngruppe', 'Fach mit id ' . $array['fachID'] . ' existiert nicht.', $array['id']);
                    return false;
                }

                return true;
            })
            // Remap the keys and unset unused valued
            ->map(function (array $array): array {
                $array['fach_id'] = $array['fachID'];
                unset($array['fachID']);
                return $array;
            })
            // Check if "kursartID" is set to NULL which indicates this "Lerngruppe" is assigned to a class.
            // Check if "klasse" exists
            ->filter(function (array $array): bool {
                if (!is_null($array['kursartID'])) {
                    return true;
                }

                // Check if "Klasse" exists
                if (Klasse::where(['id' => $array['kID']])->doesntExist()) {
                    $this->setStatus('lerngruppe', 'Klasse mit id ' . $array['kID'] . ' existiert nicht.', $array['id']);
                    return false;
                }

                return true;
            })
            // Check if "kursartID" is set to NULL which indicates this "Lerngruppe" is assigned to a class.
            // Therefor we dont need the kursartID anymore.
            ->map(function (array $array): array {
                if (is_null($array['kursartID'])) {
                    $array['klasse_id'] = $array['kID'];
                    unset($array['kursartID']);
                }

                return $array;
            })
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'lerngruppen', 'lehrerID'))
            ->filter(function (array $array): bool {
                // Filter out non integer values
                $lehrerIds = array_filter($array['lehrerID'], fn (int|string|null $value): bool => is_int($value));

                // Check if there are any IDs in the element
                if (0 === count($lehrerIds)) {
                    $this->setStatus('lerngruppen', '"lehrerID" ist leer.', $array['id']);
                    return false;
                }

                // Check if all users with corresponding lehrerID exists. If not, log and continue.
                if (User::whereIn('ext_id', $lehrerIds)->count() !== count($lehrerIds)) {
                    $this->setStatus('lerngruppen', 'Nicht alle Lehrer existieren bereits.', $array['id']);
                    return false;
                }

                return true;
            })
            ->each(function (array $array): void {
                $lerngruppe = Lerngruppe::create(Arr::except($array, 'lehrerID'));
                $lerngruppe->lehrer()->attach($array['lehrerID']);
            });
    }


    public function importFloskelgruppen(): void
    {
        if ($this->keyMissingOrEmpty($this->data, 'floskelgruppen', 'global')) {
            return;
        }

        $floskelgruppen = Floskelgruppe::all();

        collect($this->data['floskelgruppen'])
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'floskelgruppen', 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'floskelgruppen', $floskelgruppen, 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'floskelgruppen', 'bezeichnung'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'floskelgruppen', 'hauptgruppe'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'floskelgruppen', 'floskeln'))
            ->each(fn (array $array): Floskelgruppe => Floskelgruppe::create(Arr::except($array, 'floskeln')));
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
        if ($this->keyMissingOrEmpty($this->data, 'schueler', 'global')) {
            return;
        }

        $schueler = Schueler::all();

        collect($this->data['schueler'])
            ->filter(fn (array $array): bool => $this->hasValidId($array, 'schueler', $schueler))
            // Check if "Jahrgang" is set and exists in database.
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'schueler', 'jahrgangID'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'schueler', 'geschlecht'))
            ->filter(function (array $array): bool {
                if (Jahrgang::where(['id' => $array['jahrgangID']])->doesntExist()) {
                    $this->setStatus('schueler', 'Jahrgang mit id ' . $array['jahrgangID'] . ' existiert nicht.', $array['id']);
                    return false;
                }

                return true;
            })
            // Remap the keys and unset unused valued
            ->map(function (array $array): array {
                $array['jahrgang_id'] = $array['jahrgangID'];
                unset($array['jahrgangID']);
                return $array;
            })
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'schueler', 'klasseID'))
            ->filter(function (array $array): bool {
                if (Klasse::where(['id' => $array['klasseID']])->doesntExist()) {
                    $this->setStatus('schueler', 'Klasse mit id ' . $array['klasseID'] . ' existiert nicht.', $array['id']);
                    return false;
                }

                return true;
            })
            // Remap the keys and unset unused valued
            ->map(function (array $array): array {
                $array['klasse_id'] = $array['klasseID'];
                $array['geschlecht'] = $this->gender($array, Schueler::GENDERS);

                unset($array['klasseID']);
                // TODO: Temporary unset. TO BE CLEARED
                unset($array['sprachenfolge'], $array['zp10'], $array['bkabschluss']);

                return $array;
            })
            ->each(
                fn (array $array): Schueler =>
                Schueler::create(Arr::except($array, ['bemerkungen', 'lernabschnitt', 'leistungsdaten']))
            );
    }

    /**
     * Creates the Leistung model. The model will be updated with future requests.
     * The timestamp will be compared to check if the data was updated on the SVWS server.
     * TODO: ^^^ Date missing. To be cleared which timestamp for which columns should be valid.
     *
     * @return void
     */
    public function importLeistungsdaten(): void
    {
        // Check if "Schueler" are set
        if ($this->keyMissingOrEmpty($this->data, 'schueler', 'leistungsdaten')) {
            return;
        }

        // Prefetch some data
        $lerngruppen = Lerngruppe::all()->pluck('id', 'id')->toArray();
        $leistungen = Leistung::all()->pluck('id', 'id')->toArray();
        $noten = Note::all()->pluck('id', 'kuerzel')->toArray();
        $schueler = Schueler::all()->pluck('id', 'id')->toArray();

        // Perfom the upsert
        $upsert = function (array $array, $schueler) use ($noten): void {
            // Remap some fields to Laravel notation
            $array['note_id'] = $noten[$array['note']] ?? null;
            $array['lerngruppe_id'] = $array['lerngruppenID'];

            $excluded = [
                'lerngruppenID', 'note', 'teilleistungen', 'noteQuartal', 'tsNoteQuartal',
            ];
            foreach($excluded as $current) {
                unset($array[$current]);
            }

            $leistung = Leistung::firstOrNew(
                ['id' => $array['id'], 'schueler_id' => $schueler['id']],
                $array
            );

            // Check if timestamps for some fields are latter than the ones stored in DB.
            $this->updateWhenRecent($array, $leistung, 'note_id', 'tsNote');
            $this->updateWhenRecent($array, $leistung, 'fehlstundenFach', 'tsFehlstundenFach');
            $this->updateWhenRecent($array, $leistung, 'fehlstundenUnentschuldigtFach', 'tsFehlstundenUnentschuldigtFach');
            $this->updateWhenRecent($array, $leistung, 'fachbezogeneBemerkungen', 'tsFachbezogeneBemerkungen');
            $this->updateWhenRecent($array, $leistung, 'istGemahnt', 'tsIstGemahnt');

            $leistung->save();
        };

        collect($this->data['schueler'])
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'leistungsdaten', 'leistungsdaten'))
            ->filter(fn (array $array): bool => in_array($array['id'], $schueler))
            ->each(function (array $schueler) use ($upsert, $noten, $leistungen, $lerngruppen): void {
                collect($schueler['leistungsdaten'])
                    ->filter(fn (array $array): bool => $this->hasValidInt($array, 'leistungsdaten', 'id'))
                    // Check if "lerngruppenID is set
                    ->filter(fn (array $array): bool => $this->hasValidValue($array, 'leistungsdaten', 'lerngruppenID'))
                    // Chech if a "Lerngruppe" with the id exists
                    ->filter(fn (array $array): bool => array_key_exists($array['lerngruppenID'], $lerngruppen))
                    // Check if "Note is set"
                    ->filter(fn (array $array): bool => array_key_exists('note', $array))
                    // Check if either "Note" is empty, or one of already created "Noten"
                    ->filter(
                        fn (array $array): bool =>
                        in_array($array['note'], ['', null]) || array_key_exists($array['note'], $noten)
                    )
                    // Perform the upsert
                    ->each(fn (array $array) => $upsert($array, $schueler));
            });
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
        if ($this->keyMissingOrEmpty($this->data, 'schueler', 'global')) {
            return;
        }

        $schueler = Schueler::all();

        collect($this->data['schueler'])
            ->filter(fn (array $array): bool => $schueler->contains($array['id']))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'bemerkungen', 'bemerkungen'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array['bemerkungen'], 'bemerkungen', 'tsASV'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array['bemerkungen'], 'bemerkungen', 'tsAUE'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array['bemerkungen'], 'bemerkungen', 'tsZB'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array['bemerkungen'], 'bemerkungen', 'tsIndividuelleVersetzungsbemerkungen'))
            ->each(function (array $array): void {
                // Check if "Bemerkung" already exists. If not, create a new one.
                $bemerkung = Bemerkung::firstOrNew(['schueler_id' => $array['id']]);

                $data = $array['bemerkungen'];
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
            });
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
     * Import "Schueler" "Bemerkungen"
     *
     * @param Schueler $schueler
     * @param array $data
     * @return void
     */
    public function importTeilleistungsarten(): void
    {
        $key = 'teilleistungsarten';

        if ($this->keyMissingOrEmpty($this->data, $key, 'global')) {
            return;
        }

        $collection = Teilleistungsart::all();

        collect($this->data[$key])
            ->filter(fn (array $array): bool => $this->hasValidId($array, $key, $collection))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, $key, 'bezeichnung'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, $key, $collection, 'bezeichnung'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, $key, 'bezeichnung'))
            ->filter(fn (array $array): bool => $this->hasValidInt($array, $key, 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, $key, $collection, 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasValidInt($array, $key, 'gewichtung'))
            ->each(fn (array $array): Teilleistungsart => Teilleistungsart::create($array));
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
        Leistung|Lernabschnitt|Bemerkung &$model,
        string $column,
        string $tsColumn,
        int|null $value = null
    ): Leistung|Lernabschnitt|Bemerkung {

        $timestamp = Carbon::parse($data[$tsColumn]);

        if ($model->$tsColumn == null) {
            return $model;
        }
        if ($timestamp->gt($model->$tsColumn)) {
            $model->$column = $value ?? $data[$column];
            $model->$tsColumn = $timestamp->format('Y-m-d H:i:s.u');

            $this->setSuccessStatus(
                'timestamp',
                'Datensatz wurde aktualisiert da es einen neuren Timestamp hat',
                $data,
            );
        }

        return $model;
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

    private function setSuccessStatus(string $id, string $message, array|string|int|null $data = null): void
    {
        $this->setStatus($id, $message, $data, 'success');
    }

    private function setStatus(string $id, string $message, array|string|int|null $data = null, string $type = 'errors'): void
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
    private function keyMissingOrEmpty(array $row, string $key, string $context): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "{$key} nicht vorhanden", $row);
            return true;
        }

        if (is_null($row[$key])) {
            $this->setStatus($context, "{$key} ist leer", $row);
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

    /**
     * Checks if given element has valid id
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @param string $key
     * @return bool
     */
    private function hasValidId(array $row, string $context, Collection $existing, string $key = 'id'): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "'{$key}' nicht vorhanden");
            return false;
        }

        $id = $row[$key];

        if (!is_int($id)) {
            $this->setStatus($context, "'{$key}' ist keine Zahl", $id);
            return false;
        }

        if ($id <= 0) {
            $this->setStatus($context, "'{$key}' ist nicht positiv", $id);
            return false;
        }

        if ($existing->contains($id)) {
            $this->setStatus($context, "Ressource mit diesen '{$key}' existiert bereits", $id);
            return false;
        }

        return true;
    }

    /**
     * Checks if given element has valid key
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @param string $key
     * @return bool
     */
    private function hasValidValue(array $row, string $context, string $key): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "'{$key}' nicht vorhanden");
            return false;
        }

        if (is_null($row[$key])) {
            $this->setStatus($context, "'{$key}' ist leer", $row[$key]);
            return false;
        }

        return true;
    }

    /**
     * Checks if given element has an unique key
     *
     * @param array $row
     * @param string $model
     * @param Collection $existing
     * @param string $key
     * @return bool
     */
    private function hasUniqueValue(array $row, string $context, Collection $existing, string $key): bool
    {
        if ($existing->filter(fn (mixed $item): bool => $item[$key] ==  $row[$key])->count() > 0) {
            $this->setStatus($context, "Ressource mit diesen '{$key}' existiert bereits", $row[$key]);
            return false;
        }

        return true;
    }

    /**
     * Checks if given element has valid integer
     *
     * @param array $row
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function hasValidInt(array $row, string $context, string $key): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "{$key} nicht vorhanden");
            return false;
        }

        $value = $row[$key];

        if (is_null($value)) {
            $this->setStatus($context, "{$key} ist leer", $value);
            return false;
        }

        if (!is_int($value)) {
            $this->setStatus($context, "{$key} kein Zahl", $value);
            return false;
        }

        if (0 > $value) {
            $this->setStatus($context, "{$key} ist negativ", $value);
            return false;
        }

        return true;
    }
}
