<?php

namespace App\Services;

use App\Models\BKAbschluss;
use App\Models\Daten;
use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Foerderschwerpunkt;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Leistung;
use App\Models\Lerngruppe;
use App\Models\User as Lehrer;
use App\Models\Note;
use App\Models\Schueler;
use App\Models\Teilleistungsart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataImportService
{
    private mixed $json;

    public function __construct(string $json)
    {
        $this->json = json_decode($json, true);
    }

    public function import(): void
    {
        $this->importLehrer();
        $this->importDaten();
        $this->importNoten();
        $this->importFoerderschwerpunkte();
        $this->importFaecher();
        $this->importTeilleistungsarten();
        $this->importJahrgaenge();
        $this->importFloskelgruppen();
        $this->importKlassen();
        $this->importLerngruppen();
        $this->importSchueler();
    }

    private function importDaten(): void
    {
        Daten::updateOrCreate(
            ['user_id' => Lehrer::where('ext_id', $this->json['lehrerID'])->firstOrFail()->id],
            [
                'enmRevision' => $this->json['enmRevision'],
                'schulnummer' => $this->json['schulnummer'],
                'schuljahr' => $this->json['schuljahr'],
                'anzahlAbschnitte' => $this->json['anzahlAbschnitte'],
                'aktuellerAbschnitt' => $this->json['aktuellerAbschnitt'],
                'publicKey' => $this->json['publicKey'],
                'lehrerID' => $this->json['lehrerID'],
                'fehlstundenEingabe' => $this->json['fehlstundenEingabe'],
                'fehlstundenSIFachbezogen' => $this->json['fehlstundenSIFachbezogen'],
                'fehlstundenSIIFachbezogen' => $this->json['fehlstundenSIIFachbezogen'],
                'schulform' => $this->json['schulform'],
                'mailadresse' => $this->json['mailadresse'],
            ]
        );
    }

    private function importLehrer(): void
    {
        collect($this->json['lehrer'])->each(fn (array $row) => Lehrer::updateOrCreate(
            ['ext_id' => $row['id']],
            [
                'kuerzel' => $row['kuerzel'],
                'nachname' => $row['nachname'],
                'vorname' => $row['vorname'],
                'email' => $row['eMailDienstlich'] ?? sprintf('%s@%s', Str::random(), Str::random()),
                'password' => /*Str::random() */ Hash::make('password'),
            ],
        ));
    }

    private function importNoten(): void
    {
        collect($this->json['noten'])->each(fn (array $row) =>
            Note::updateOrCreate(['kuerzel' => $row['kuerzel']], $row)
        );
    }

    private function importFoerderschwerpunkte(): void
    {
        collect($this->json['foerderschwerpunkte'])->each(fn (array $row) =>
            Foerderschwerpunkt::updateOrCreate(['kuerzel' => $row['kuerzel']], $row)
        );
    }

    private function importFaecher(): void
    {
        collect($this->json['faecher'])->each(fn (array $row) =>
            Fach::updateOrCreate(['ext_id' => $row['id']], $row)
        );
    }

    private function importTeilleistungsarten(): void
    {
        collect($this->json['teilleistungsarten'])->each(fn (array $row) =>
            Teilleistungsart::updateOrCreate(['ext_id' => $row['id']], $row)
        );
    }

    private function importJahrgaenge(): void
    {
        collect($this->json['jahrgaenge'])->each(fn (array $row) =>
            Jahrgang::updateOrCreate(['ext_id' => $row['id']], $row)
        );
    }

    private function importFloskelgruppen(): void
    {
        foreach ($this->json['floskelgruppen'] as $row) {
            $floskelgruppe = Floskelgruppe::firstOrCreate(
                ['kuerzel' => $row['kuerzel']],
                Arr::except($row, ['floskeln'])
            );

            $this->importFloskeln($floskelgruppe, $row);
        }
    }

    private function importFloskeln(Floskelgruppe $floskelgruppe, array $data): void
    {
        if (!$data['floskeln']) {
            return;
        }

        foreach ($data['floskeln'] as $row) {
            $floskel = $floskelgruppe->floskeln()->make(Arr::except($row, ['fachID', 'jahrgangID']));
            $floskel->fach_id = $this->getRelation($row, Fach::class, 'fachID');
            $floskel->jahrgang_id = $this->getRelation($row, Jahrgang::class, 'jahrgangID');
            $floskel->save();
        }
    }

    private function importKlassen(): void
    {
        collect($this->json['klassen'])->each(function (array $row) {
            $klasse = Klasse::firstOrCreate(['ext_id' => $row['id']], Arr::except($row, ['klassenlehrer']));

            collect($row['klassenlehrer'])->each(fn ($klassenlehrer) =>
                $klasse->klassenlehrer()->syncWithoutDetaching([
                    Lehrer::where('ext_id', $klassenlehrer)->firstOrFail()->id
                ])
            );
        });
    }

    private function getGroupableId(array $row): int
    {
        if ($row['kursartID'] === null) {
            return Klasse::where('ext_id', $row['kID'])->firstOrFail()->id;
        }

        return Kurs::firstOrCreate(
            ['ext_id' => $row['kursartID']],
            ['bezeichnung' => $row['bezeichnung'], 'kuerzel' => $row['kursartKuerzel']]
        )->id;
    }

    private function getGroupableType(array $row): string
    {
        return $row['kursartID'] !== null ? Kurs::class : Klasse::class;
    }

    private function importLerngruppen(): void
    {
        $excluded = ['ext_id', 'lehrerID', 'fachID', 'kID', 'kursartKuerzel', 'user_id'];

        foreach ($this->json['lerngruppen'] as $row) {
            $lerngruppe = Lerngruppe::firstOrNew(['ext_id' => $row['id']], Arr::except($row, $excluded));
            $lerngruppe->fach_id =  $this->getRelation($row, Fach::class, 'fachID');
            $lerngruppe->groupable_type = $this->getGroupableType($row);
            $lerngruppe->groupable_id = $this->getGroupableId($row);
            $lerngruppe->save();

            $this->importLerngruppenLehrer($lerngruppe, $row);
        }
    }

    private function importLerngruppenLehrer(Model|Lerngruppe $model, array $data): void
    {
        if (!$data['lehrerID']) {
            return;
        }

        foreach ($data['lehrerID'] as $lehrer) {
            $model->lehrer()->attach(Lehrer::where('ext_id', $lehrer)->firstOrFail()->id);
        }
    }

    private function importSchueler(): void
    {
        foreach ($this->json['schueler'] as $row) {
            $schueler = Schueler::firstOrNew(['ext_id' => $row['id']]);
            $schueler->nachname = $row['nachname'];
            $schueler->vorname = $row['vorname'];
            $schueler->bilingualeSprache = $row['bilingualeSprache'];
            $schueler->istZieldifferent = $row['istZieldifferent'];
            $schueler->istDaZFoerderung = $row['istDaZFoerderung'];
            $schueler->jahrgang_id = Jahrgang::where('ext_id', $row['jahrgangID'])->firstOrFail()->id;
            $schueler->klasse_id = Klasse::where('ext_id', $row['klasseID'])->firstOrFail()->id;
            $schueler->geschlecht = $this->gender($row);

			// Bemerkungen
			if ($row['bemerkungen'] != null) {
				dd($row['bemerkungen']);
				$schueler->asv = $this->getBemerkung($row['bemerkungen'], 'asv');
				$schueler->aue = $this->getBemerkung($row['bemerkungen'], 'aue');
				$schueler->zb = $this->getBemerkung($row['bemerkungen'], 'zb');
				$schueler->lels = $this->getBemerkung($row['bemerkungen'], 'lels');
				$schueler->schulformEmpf = $this->getBemerkung($row['bemerkungen'], 'schulformEmpf');
				$schueler->individuelleVersetzungsbemerkungen = $this->getBemerkung($row['bemerkungen'], 'individuelleVersetzungsbemerkungen');
				$schueler->foerderbemerkungen = $this->getBemerkung($row['bemerkungen'], 'foerderbemerkungen');
			}
            $schueler->save();

            $this->importSprachenfolge($schueler, $row);
            $this->importLernabschnitte($schueler, $row);
            $this->importLeistungen($schueler, $row);
            $this->importZp10($schueler, $row);
            $this->importBkAbschluss($schueler, $row);
        }
    }

	private function getBemerkung(array|null $array, $key): null|string
	{
		return array_key_exists($key, $array) ? $array[$key] : null;
	}

    private function importLeistungen(Model|Schueler $model, array $data): void
    {
        if (!$data['leistungsdaten']) {
            return;
        }

        $excluded = ['lerngruppenID', 'note', 'teilleistungen'];

        foreach ($data['leistungsdaten'] as $row) {
            $leistung = $model->leistungen()->firstOrNew(['ext_id' => $row['id']], Arr::except($row, $excluded));
            $leistung->lerngruppe_id = $this->getRelation($row, Lerngruppe::class, 'lerngruppenID');
            $leistung->note_id = $this->getRelation($row, Note::class, 'note', 'kuerzel');
            $leistung->save();
            $this->importTeilleistungen($leistung, $row);
        }
    }

    private function importTeilleistungen(Model|Leistung $model, array $data): void
    {
        if (!$data['teilleistungen']) {
            return;
        }

        foreach ($data['teilleistungen'] as $row) {
            $teilleistung = $model->teilleistungen()->firstOrNew(
                ['ext_id' => $row['id']],
                Arr::only($row, ['datum', 'bemerkung']),
            );

            $teilleistung->teilleistungsart_id = $this->getRelation($row, Teilleistungsart::class, 'artID');
            $teilleistung->note_id = $this->getRelation($row, Note::class, 'notenKuerzel', 'kuerzel');
            $teilleistung->save();
        }
    }

    private function importSprachenfolge(Model|Schueler $model, array $data): void
    {
        foreach ($data['sprachenfolge'] as $row) {
            $sprachenfolge = $model->sprachenfolgen()->make(Arr::except($row, ['fachID', 'sprache', 'fachKuerzel']));

            $sprachenfolge->fach_id = Fach::firstOrCreate(
                ['ext_id' => $row['fachID']],
                ['fachKuerzel' => $row['sprache'], 'kuerzelAnzeige' => $row['fachKuerzel']]
            )->id;

            $sprachenfolge->save();
        }
    }

    public function importLernabschnitte(Model|Schueler $model, array $data): void
    {
        if (!$data['lernabschnitt']) {
            return;
        }

        $row = $data['lernabschnitt'];

        $lernabschnitt = $model->lernabschnitt()->firstOrNew(['ext_id' => $row['id']]);
        $lernabschnitt->pruefungsordnung = $row['pruefungsordnung'];
        $lernabschnitt->lernbereich1note = $this->getRelation($row, Note::class, 'lernbereich1note', 'kuerzel');
        $lernabschnitt->lernbereich2note = $this->getRelation($row, Note::class, 'lernbereich2note', 'kuerzel');
        $lernabschnitt->foerderschwerpunkt1 = $this->getRelation($row, Foerderschwerpunkt::class, 'foerderschwerpunkt1', 'kuerzel');
        $lernabschnitt->foerderschwerpunkt2 = $this->getRelation($row, Foerderschwerpunkt::class, 'foerderschwerpunkt2', 'kuerzel');
        $lernabschnitt->save();
    }

    public function importZp10(Model|Schueler $model, array $data): void
    {
        if (!$data['zp10']) {
            return;
        }

        $row = $data['zp10'];

        $zp10 = $model->zp10()->firstOrNew(['ext_id' => $row['id']], Arr::except($row, ['fachID']));
        $zp10->fach_id = $this->getRelation($row, Fach::class, 'vornote');
        $zp10->vornote = $this->getRelation($row, Note::class, 'vornote', 'kuerzel');
        $zp10->noteSchriftlichePruefung = $this->getRelation($row, Note::class, 'noteSchriftlichePruefung', 'kuerzel');
        $zp10->noteMuendlichePruefung = $this->getRelation($row, Note::class, 'noteMuendlichePruefung', 'kuerzel');
        $zp10->abschlussnote = $this->getRelation($row, Note::class, 'abschlussnote', 'kuerzel');
        $zp10->save();
    }

    public function importBkAbschluss(Model|Schueler $model, array $data): void
    {
        if (!$data['bkabschluss']) {
            return;
        }

        $row = $data['bkabschluss'];
        $excluded = ['notePraktischePruefung', 'noteKolloqium', 'noteFachpraxis', 'faecher'];

        $bkAbschluss = $model->bkabschluss()->firstOrNew(['ext_id' => $row['id']], Arr::except($row, $excluded));
        $bkAbschluss->notePraktischePruefung = $this->getRelation($row, Note::class, 'notePraktischePruefung', 'kuerzel');
        $bkAbschluss->noteKolloqium = $this->getRelation($row, Note::class, 'noteKolloqium', 'kuerzel');
        $bkAbschluss->noteFachpraxis = $this->getRelation($row, Note::class, 'noteFachpraxis', 'kuerzel');
        $bkAbschluss->save();

        $this->importBkAbschlussFach($bkAbschluss, $row['faecher']);
    }

    public function importBkAbschlussFach(Model|BKAbschluss $model, array $data): void
    {
        if (!$data['faecher']) {
            return;
        }

        $row = $data['faecher'];

        $bkFach = $model->bkFaecher()->make(Arr::except($data, ['fachID', 'lehrerID']));
        $bkFach->fach_id = $this->getRelation($row, Fach::class, 'fachID');
        $bkFach->lehrer_id = $this->getRelation($row, Lehrer::class, 'lehrerID');
        $bkFach->vornote = $this->getRelation($row, Note::class, 'vornote', 'kuerzel');
        $bkFach->noteSchriftlichePruefung = $this->getRelation($row, Note::class, 'noteSchriftlichePruefung', 'kuerzel');
        $bkFach->noteMuendlichePruefung = $this->getRelation($row, Note::class, 'noteMuendlichePruefung', 'kuerzel');
        $bkFach->noteBerufsabschluss = $this->getRelation($row, Note::class, 'noteBerufsabschluss', 'kuerzel');
        $bkFach->abschlussnote = $this->getRelation($row, Note::class, 'abschlussnote', 'kuerzel');
        $bkFach->save();
    }

    private function getRelation(
        array $data,
        string $class,
        string $currentKey,
        string $foreignKey = 'ext_id',
        string $value = 'id'
    ): int|string|null {
        if (!$data[$currentKey]) {
            return null;
        }

        try {
            return $class::where($foreignKey, $data[$currentKey])->firstOrFail()->$value;
        } catch (ModelNotFoundException $e) {
            dd($e->getMessage());
        }
    }

    public function gender(array $data): string
    {
        $allowed = ['m', 'w', 'd', 'x'];

        if (array_key_exists('geschlecht', $data) && in_array($data['geschlecht'], $allowed)) {
            return $data['geschlecht'];
        }

        return 'x';
    }
}

