<?php

namespace App\Services;

use App\Models\{
    Bemerkung, Fach, Floskelgruppe, Floskel, Foerderschwerpunkt, Jahrgang, Klasse, Leistung, Lernabschnitt, Lerngruppe,
    Note, Schueler, User,
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\{Arr, Str};
use Illuminate\Support\Facades\Validator;

class DataImportService
{
    /**
     * Import status with error and success messages.
     *
     * @var array $status
     */
    private array $status = [
        'errors' => [],
        'success' => [],
    ];

    /**
     * Class constructor
     *
     * @param array $data
     */
    public function __construct(private array $data = [])
    {}

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
        $this->importLernabschnitte();

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
    public function importLehrer(string $ctx = 'lehrer'): void
    {
        if ($this->keyMissingOrEmpty($this->data, $ctx)) {
            return;
        }

        // Check if email is correct. If not, fallback to temporary random email that will be updated in the future.
        $prepareEmail = function (array $row) use ($ctx): array {
            $email = $row['email'] = $row['eMailDienstlich'] ?? null;

            // Sets notification that the email is not valid. A temporary email will be assigned in UserObserver
            if (!$email || $email == '' || is_null($email)) {
                $this->setStatus($ctx, '"eMailDienstlich" ist leer oder ungueltig', $row['id']);
            }

            unset($row['eMailDienstlich']);

            return $row;
        };

        collect($this->data[$ctx])
            ->map(fn(array $array): array => $prepareEmail($array))
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
    public function importJahrgaenge(string $ctx = 'jahrgaenge'): void
    {
        if ($this->keyMissingOrEmpty($this->data, $ctx)) {
            return;
        }

        // Fetch the values to compare in the future
        $jahrgaenge = Jahrgang::all();
        $ids = $jahrgaenge->pluck('id', 'id')->toArray();
        $kuerzeln = $jahrgaenge->pluck('id', 'kuerzel')->toArray();
        $sortierungen = $jahrgaenge->pluck('id', 'sortierung')->toArray();

        collect($this->data['jahrgaenge'])
            ->filter(fn (array $array): bool => $this->hasValidId($array, 'jahrgaenge', $jahrgaenge))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'jahrgaenge', $jahrgaenge, 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'jahrgaenge', $jahrgaenge, 'sortierung'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'kuerzelAnzeige'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'stufe'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgaenge', 'beschreibung'))
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
    public function importKlassen(string $ctx = 'klassen'): void
    {
        if ($this->keyMissingOrEmpty($this->data, $ctx)) {
            return;
        }

        // Prefetch data for future use
        $klassen = Klasse::all();
        $ids = $klassen->pluck('id', 'id')->toArray();
        $kuerzeln = $klassen->pluck('id', 'kuerzel')->toArray();
        $sortierungen = $klassen->pluck('id', 'sortierung')->toArray();
        $jahrgaenge = Jahrgang::all()->pluck('id', 'id')->toArray();
        $klassenlehrer = fn (array $row): array => User::query()
            ->whereIn('ext_id', (array) $row['klassenlehrer'])
            ->pluck('ext_id')
            ->toArray();

        collect($this->data[$ctx])
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'id', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'id', $ids, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'kuerzel'))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'kuerzel', $kuerzeln, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'sortierung', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'sortierung', $sortierungen, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'idJahrgang', $ctx))
            ->filter(fn (array $array): bool => $this->relationExists($array, 'idJahrgang', $jahrgaenge, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'klassenlehrer', $ctx))
            ->filter(function (array $array) use ($ctx): bool {
                if (count($array['klassenlehrer']) <= 0) {
                    $this->setStatus($ctx, '"klassenlehrer" ist leer', $array['klassenlehrer']);
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
     * @return void
     */
    public function importNoten(string $ctx = 'noten'): void
    {
        if ($this->keyMissingOrEmpty($this->data, $ctx)) {
            return;
        }

        $noten = Note::all();
        $ids = $noten->pluck('id', 'id')->toArray();
        $kuerzeln = $noten->pluck('id', 'kuerzel')->toArray();

        collect($this->data[$ctx])
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'id', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'id', $ids, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'kuerzel', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'kuerzel', $kuerzeln, $ctx))
            ->each(fn (array $array): Note => Note::create($array));
    }

    /**
     * Creates the Foerderschwerpunkt model.
     * The model will not be updated with future requests.
     *
     * @return void
     */
    public function importFoerderschwerpunkte(string $ctx = 'foerderschwerpunkte'): void
    {
        if ($this->keyMissingOrEmpty($this->data, $ctx)) {
            return;
        }

        $foerderschwerpunkte = Foerderschwerpunkt::all();
        $ids = $foerderschwerpunkte->pluck('id', 'id')->toArray();
        $kuerzeln = $foerderschwerpunkte->pluck('id', 'kuerzel')->toArray();

        collect($this->data['foerderschwerpunkte'])
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'id', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'id', $ids, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'kuerzel', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'kuerzel', $kuerzeln, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'beschreibung', $ctx))
            ->each(fn (array $array): Foerderschwerpunkt => Foerderschwerpunkt::create($array));
    }

    /**
     * Creates the Fach model.
     * The model will not be updated with future requests.
     *
     * @return void
     */
    public function importFaecher(string $ctx = 'faecher'): void
    {
        if ($this->keyMissingOrEmpty($this->data, $ctx)) {
            return;
        }

        $faecher = Fach::all();
        $ids = $faecher->pluck('id', 'id')->toArray();
        $kuerzeln = $faecher->pluck('id', 'kuerzel')->toArray();
        $sortierungen = $faecher->pluck('id', 'sortierung')->toArray();

        collect($this->data['faecher'])
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'id', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'id', $ids, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'kuerzel', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'kuerzel', $kuerzeln, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'sortierung', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'sortierung', $sortierungen, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'istFremdsprache', $ctx))
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
    public function importLerngruppen(string $ctx = 'lerngruppen'): void
    {
        if ($this->keyMissingOrEmpty($this->data, $ctx)) {
            return;
        }

        $lerngruppen = Lerngruppe::all()->pluck('id', 'id')->toArray();
        $faecher = Fach::all()->pluck('id', 'id')->toArray();
        $klassen = Klasse::all()->pluck('id', 'id')->toArray();

        collect($this->data['lerngruppen'])
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'id', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'id', $lerngruppen, $ctx))
            // Check if "kID" is present and properly formatted.
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'kID', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'bezeichnung', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'wochenstunden', $ctx))
            // Check if related "Fach" is valid and exists
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'fachID', $ctx))
            ->filter(fn (array $array): bool => $this->relationExists($array, 'fachID', $faecher, $ctx))
            // Check if "kursartID" is set to NULL which indicates this "Lerngruppe" is assigned to a "Klasse".
            ->filter(function (array $array) use ($ctx, $klassen): bool {
                if (!is_null($array['kursartID'])) {
                    return true;
                }

                // Check if "Klasse" exists
                if (!array_key_exists($array['kID'], $klassen)) {
                    $this->setStatus($ctx, 'Klasse mit id ' . $array['kID'] . ' existiert nicht.', $array['id']);
                    return false;
                }

                return true;
            })
            // Remap the keys
            ->map(fn (array $array): array => [...$array, ...['fach_id' => $array['fachID']]])
            // Check if "kursartID" is set to NULL which indicates this "Lerngruppe" is assigned to a class.
            // Therefor we dont need the kursartID anymore.
            ->map(function (array $array): array {
                if (is_null($array['kursartID'])) {
                    $array['klasse_id'] = $array['kID'];
                    unset($array['kursartID']);
                }

                return $array;
            })
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'lehrerID', $ctx))
            ->filter(function (array $array) use ($ctx): bool {
                // Filter out non integer values
                $lehrerIds = array_filter($array['lehrerID'], fn (int|string|null $value): bool => is_int($value));

                // Check if there are any IDs in the element
                if (0 === count($lehrerIds)) {
                    $this->setStatus($ctx, '"lehrerID" ist leer.', $array['id']);
                    return false;
                }

                // Check if all users with corresponding lehrerID exists. If not, log and continue.
                if (User::whereIn('ext_id', $lehrerIds)->count() !== count($lehrerIds)) {
                    $this->setStatus($ctx, 'Nicht alle Lehrer existieren bereits.', $array['id']);
                    return false;
                }

                return true;
            })
            ->each(function (array $array): void {
            $lerngruppe = Lerngruppe::create(Arr::except($array, 'lehrerID'));
                $lerngruppe->lehrer()->attach($array['lehrerID']);
            });
    }


    /**
     * Import Floskelgruppe
     *
     * @return void
     */
    public function importFloskelgruppen(string $ctx = 'floskelgruppen'): void
    {
        if ($this->keyMissingOrEmpty($this->data, $ctx)) {
            return;
        }

        $kuerzeln = Floskelgruppe::all()->pluck('id', 'kuerzel')->toArray();

        collect($this->data['floskelgruppen'])
<<<<<<< HEAD
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'kuerzel', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'kuerzel', $kuerzeln, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'bezeichnung', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'hauptgruppe', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'floskeln', $ctx))
            ->filter(function (array $array) use ($ctx): bool {
                if (count($array['floskeln']) > 0) {
                    return true;
                }

                $this->setStatus($ctx, 'Floskelgruppe beinhaltet keine Floskeln', $array['kuerzel']);
                return false;
            })
            ->each(function (array $array): void {
                $floskelgruppe = Floskelgruppe::create($array);
                $this->importFloskeln($floskelgruppe, $array['floskeln']);
            });
    }

    /**
     * Imports floskeln
     * Since "Floskeln" are a subset of "Floskelgruppe" this has to be checked first.
     *
     * @param Floskelgruppe $floskelgruppe
     * @param array $floskeln
     * @return void
     */
    private function importFloskeln(Floskelgruppe $floskelgruppe, array $floskeln, string $ctx = 'floskeln'): void
    {
        // Prefetch all comparison data.
        $faecher = Fach::all()->pluck('id', 'id')->toArray();
        $kuerzeln = Floskel::all()->pluck('id', 'kuerzel')->toArray();
        $jahrgaenge = Jahrgang::all()->pluck('id', 'id')->toArray();

        collect($floskeln)
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'kuerzel', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'kuerzel', $kuerzeln, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'niveau', $ctx, true))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'text', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgangID', $ctx, true))
            ->filter(fn (array $array): bool =>
                is_null($array['jahrgangID']) || $this->relationExists($array, 'jahrgangID', $jahrgaenge, $ctx)
            )
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'fachID', $ctx, true))
            ->filter(fn (array $array): bool =>
                is_null($array['fachID']) || $this->relationExists($array, 'fachID', $faecher, $ctx)
            )
            ->each(fn (array $array) => $floskelgruppe->floskeln()->create($array));
=======
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'floskelgruppen', 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasUniqueValue($array, 'floskelgruppen', $floskelgruppen, 'kuerzel'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'floskelgruppen', 'bezeichnung'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'floskelgruppen', 'hauptgruppe'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'floskelgruppen', 'floskeln'))
            ->each(fn (array $array): Floskelgruppe => Floskelgruppe::create(Arr::except($array, 'floskeln')));
>>>>>>> feature/308-download-wirft-Fehler
    }

    /**
     * Creates the Schueler model. The model will not be updated with future requests.
     * Related Lernabschnitt model will be created only if the Lerngruppe model was recently created.
     * TODO: ^^^ To be cleared with Customer
     *
     * @return void
     */
    public function importSchueler(string $ctx = 'schueler'): void
    {
        if ($this->keyMissingOrEmpty($this->data, $ctx)) {
            return;
        }

        $schueler = Schueler::all()->pluck('id', 'id')->toArray();
        $jahrgaenge = Jahrgang::all()->pluck('id', 'id')->toArray();
        $klassen = Klasse::all()->pluck('id', 'id')->toArray();

        collect($this->data[$ctx])
            ->filter(fn (array $array): bool => $this->hasValidInt($array, 'id', $ctx))
            ->filter(fn (array $array): bool => !$this->modelAlreadyExists($array, 'id', $schueler, $ctx))
            // Check if "Jahrgang" is set and exists in database.
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'jahrgangID', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'geschlecht', $ctx))
            ->filter(fn (array $array): bool => $this->relationExists($array, 'jahrgangID', $jahrgaenge, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'klasseID', $ctx))
            ->filter(fn (array $array): bool => $this->relationExists($array, 'klasseID', $klassen, $ctx))
            ->map(fn (array $array): array => [...$array, ...[
                'jahrgang_id' => $array['jahrgangID'],
                'klasse_id' => $array['klasseID'],
                'geschlecht' => $this->gender($array, Schueler::GENDERS),
            ]])
            ->each(fn (array $array): Schueler => Schueler::create($array));
    }

    /**
     * Creates the Leistung model. The model will be updated with future requests.
     * The timestamp will be compared to check if the data was updated on the SVWS server.
     * TODO: ^^^ Date missing. To be cleared which timestamp for which columns should be valid.
     *
     * @return void
     */
    public function importLeistungsdaten(string $ctx = 'leistungsdaten'): void
    {
        // Check if "Schueler" are set
        if ($this->keyMissingOrEmpty($this->data, 'schueler')) {
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
            ->filter(fn (array $array): bool => $this->modelAlreadyExists($array, 'id', $schueler, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'leistungsdaten', $ctx))
            ->each(function (array $schueler) use ($upsert, $noten, $leistungen, $lerngruppen, $ctx): void {
                collect($schueler['leistungsdaten'])
                    ->filter(fn (array $array): bool => $this->hasValidInt($array, 'id', $ctx))
                    ->filter(fn (array $array): bool => $this->hasValidValue($array, 'lerngruppenID', $ctx))
                    ->filter(fn (array $array): bool => $this->modelAlreadyExists($array, 'lerngruppenID', $lerngruppen, $ctx))
                    ->filter(function (array $array) use ($ctx): bool {
                        if (array_key_exists('note', $array)) {
                            return true;
                        }

                        $this->setStatus($ctx, 'Note existiert nicht', $array['id']);
                        return false;
                    })
                    ->filter(fn (array $array): bool =>
                        in_array($array['note'], ['', null]) || $this->modelAlreadyExists($array, 'note', $noten, $ctx)
                    )
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
    public function importBemerkungen(string $ctx = 'bemerkungen'): void
    {
        if ($this->keyMissingOrEmpty($this->data, 'schueler')) {
            return;
        }

        $schueler = Schueler::all()->pluck('id','id')->toArray();

        $execute = function (array $array): void {
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
        };

        collect($this->data['schueler'])
            ->filter(fn (array $array): bool => $this->modelAlreadyExists($array, 'id', $schueler, $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'bemerkungen', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array['bemerkungen'], 'tsASV', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array['bemerkungen'], 'tsAUE', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array['bemerkungen'], 'tsZB', $ctx))
            ->filter(fn (array $array): bool => $this->hasValidValue($array['bemerkungen'], 'tsIndividuelleVersetzungsbemerkungen', $ctx))
            ->each(fn (array $array) => $execute($array));
    }

    /**
     * Creates the Lernabschnitt model.
     * TODO: Still waiting for the information if the 4 fields will have the `id` instead of the `kuerzel` // #284
     *
     * @return void
     */
    private function importLernabschnitte(string $ctx = 'lernabschnitt'): void
    {
        if ($this->keyMissingOrEmpty($this->data, 'schueler', 'global')) {
            return;
        }

        $schueler = Schueler::all()->pluck('id', 'id')->toArray();
        $foerderschwerpunkte = Foerderschwerpunkt::orderBy('kuerzel')->pluck('id', 'kuerzel')->toArray();
        $noten = Note::orderBy('kuerzel')->pluck('id', 'kuerzel')->toArray();

        $execute = function (array $array) use ($foerderschwerpunkte, $noten): void {
            // Check if "Lernabschnitt" already exists. If not, create a new one.
            $lernabschnitt = Lernabschnitt::firstOrNew(['schueler_id' => $array['id']]);
            $data = $array['lernabschnitt'];

            $lernabschnitt->pruefungsordnung = $data['pruefungsordnung'];

            $lernabschnitt['foerderschwerpunkt1'] = $this->getValueFromArray(
                $data,
                'foerderschwerpunkt1',
                $foerderschwerpunkte,
            );

            $lernabschnitt['foerderschwerpunkt2'] = $this->getValueFromArray(
                $data,
                'foerderschwerpunkt2',
                $foerderschwerpunkte,
            );

            $lernabschnitt['lernbereich1note'] = $this->getValueFromArray($data, 'lernbereich1note', $noten);
            $lernabschnitt['lernbereich2note'] = $this->getValueFromArray($data, 'lernbereich2note', $noten);

            $this->updateWhenRecent($data, $lernabschnitt, 'fehlstundenGesamt', 'tsFehlstundenGesamt');
            $this->updateWhenRecent($data, $lernabschnitt, 'fehlstundenGesamtUnentschuldigt', 'tsFehlstundenGesamtUnentschuldigt');

            $lernabschnitt->save();
        };

        collect($this->data['schueler'])
            // The "Schueler" for given "Lernabschnitt" has to exist
            ->filter(fn (array $array): bool => $this->modelAlreadyExists($array, 'id', $schueler, 'lernabscnitt'))
            ->filter(fn (array $array): bool => $this->hasValidValue($array, 'lernabschnitt', $ctx))
            ->filter(fn (array $array): bool =>
                $this->hasValidValue($array['lernabschnitt'], 'pruefungsordnung', $ctx)
            )
            ->filter(fn (array $array): bool =>
                $this->hasValidValue($array['lernabschnitt'], 'tsFehlstundenGesamt', $ctx)
            )
            ->filter(fn (array $array): bool =>
                $this->hasValidValue($array['lernabschnitt'], 'tsFehlstundenGesamtUnentschuldigt', $ctx)
            )
            ->each(fn (array $array) => $execute($array));
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
    private function keyMissingOrEmpty(array $row, string $key, string $context = 'global'): bool
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
     * @param string $key
     * @param string $context
     * @return bool
     */
    private function hasValidValue(array $row, string $key, string $context = 'general', bool $nullable = false): bool
    {
        if (!array_key_exists($key, $row)) {
            $this->setStatus($context, "'{$key}' nicht vorhanden");
            return false;
        }

        if (is_null($row[$key]) && !$nullable) {
            $this->setStatus($context, "'{$key}' ist NULL", $row[$key]);
            return false;
        }

        if ($row[$key] === '') {
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
    private function hasValidInt(array $row, string $key, string $context): bool
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

    /**
     * Check if relate model exists
     *
     * @param array $row
     * @param string $key
     * @param array $array
     * @param string $context
     * @return bool
     */
    private function relationExists(array $row, string $key, array $array, string $context): bool
    {
        if (array_key_exists($row[$key], $array)) {
            return true;
        }

        $this->setStatus($context, "{$key} existiert nicht", $row);

        return false;
    }

    /**
     * Check if model already exists
     *
     * @param array $row
     * @param string $key
     * @param array $array
     * @param string $context
     * @return bool
     */
    private function modelAlreadyExists(array $row, string|int $key, array $array, string $context): bool
    {
        if (array_key_exists($row[$key], $array)) {
            $this->setStatus($context, "{$key} existiert bereits", $row);
            return true;
        }

        return false;
    }
}
