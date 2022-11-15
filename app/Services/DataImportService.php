<?php

namespace App\Services;

use App\Models\Daten;
use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Foerderschwerpunkt;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Lerngruppe;
use App\Models\User as Lehrer;
use App\Models\Note;
use App\Models\Schueler;
use App\Models\Teilleistungsart;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataImportService
{
    private mixed $json;

	private array $noten;
	private array $foerderschwerpunkte;
	private int $microtime;

    public function __construct(string $json)
    {
        $this->json = json_decode($json, true);
    }

    public function import(): void
    {
        $this->importLehrer();
        $this->importKlassen();
        $this->importNoten();
        $this->importKursarten();
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

	public function importLehrer(): void
	{
		$this->start('Lehrer');

		foreach($this->json['lehrer'] as $row) {
			$row['email'] = $row['eMailDienstlich'] ?? Str::random() .'@'. Str::random() .'.'.  Str::random(); // TODO: https://git.svws-nrw.de/phpprojekt/webnotenmanager/-/issues/15#note_7329
			$row['password'] = app()->environment('production') ? Str::random() : Hash::make('password');
			$row['geschlecht'] = $this->gender(data: $row, allowed: Lehrer::GENDERS);

			unset($row['eMailDienstlich']);

			Lehrer::updateOrCreate(['id' => $row['id']], $row);
		}

		$this->stop();
	}

	public function importKlassen(): void
	{
		$this->start('Klassen');

		foreach($this->json['klassen'] as $row) {
			$klasse = Klasse::firstOrCreate(['ext_id' => $row['id']], Arr::except($row, ['klassenlehrer']));
			$klasse->klassenlehrer()->sync($row['klassenlehrer']);
		}

		$this->stop();
	}

	public function importNoten(): void
    {
		$this->start('Noten');

		foreach($this->json['noten'] as $row) {
			Note::updateOrCreate(['kuerzel' => $row['kuerzel']], $row);
		}

		$this->noten = Note::query()
			->orderBy('kuerzel')
			->pluck('id', 'kuerzel')
			->toArray();

		$this->stop();
    }

	public function importKursarten(): void
    {
		$this->start('Kursarten');
		echo "  ! No importer yet \r\n";

		$this->stop();
    }

	public function importFoerderschwerpunkte(): void
	{
		$this->start('foerderschwerpunkte');

		foreach($this->json['foerderschwerpunkte'] as $row) {
			Foerderschwerpunkt::updateOrCreate(['kuerzel' => $row['kuerzel']], $row);
		}

		$this->foerderschwerpunkte = Foerderschwerpunkt::query()
			->orderBy('kuerzel')
			->pluck('id', 'kuerzel')
			->toArray();

		$this->stop();
	}

	public function importJahrgaenge(): void
	{
		$this->start('Jahrgaenge');

		foreach($this->json['jahrgaenge'] as $row) {
			$row['beschreibung'] = $this->trimWhitespaces($row['beschreibung']);
			Jahrgang::updateOrCreate(['id' => $row['id']], $row);
		}

		$this->stop();
	}

	public function importFaecher(): void
	{
		$this->start('Faecher');

		foreach($this->json['faecher'] as $row) {
			Fach::updateOrCreate(['id' => $row['id']], $row);
		}

		$this->stop();
	}

	public function importFloskelgruppen(): void
	{
		$this->start('Floskelgruppen mit Floskeln');

		foreach($this->json['floskelgruppen'] as $row) {
			$floskelgruppe = Floskelgruppe::firstOrCreate(
				['kuerzel' => $row['kuerzel']],
				Arr::except($row, ['floskeln'])
			);

			foreach ($row['floskeln'] as $floskel) {
				$floskel['fach_id'] = $floskel['fachID'];
				$floskel['jahrgang_id'] = $floskel['jahrgangID'];

				unset($floskel['fachID']);
				unset($floskel['jahrgangID']);

				$floskelgruppe->floskeln()->create($floskel);
			}
		}

		$this->stop();
	}

	public function importLerngruppen(): void
	{
		$this->start('Lerngruppen');

		foreach ($this->json['lerngruppen'] as $row) {
			$row['fach_id'] = $row['fachID'];
			$row['groupable_type'] = $row['kursartID'] !== null ? Kurs::class : Klasse::class;
			$row['groupable_id'] = $row['kID'];

			unset($row['fachID']);
			unset($row['kID']);

			$lerngruppe = Lerngruppe::firstOrCreate(['id' => $row['id']], Arr::except($row, ['lehrerID']));
			$lerngruppe->lehrer()->sync($row['lehrerID']);
		}

		$this->stop();
	}

	public function importTeilleistungsarten(): void
	{
		$this->start('Teilleistungsarten');

		foreach ($this->json['teilleistungsarten'] as $row) {
			Teilleistungsart::updateOrCreate(['id' => $row['id']], $row);
		}

		$this->stop();
	}

	public function importSchueler(): void
	{
		$this->start('Schueler');

		foreach ($this->json['schueler'] as $row) {
			$row['jahrgang_id'] = $row['jahrgangID'];
			$row['klasse_id'] = $row['klasseID'];
			$row['geschlecht'] = $this->gender(data: $row, allowed: Schueler::GENDERS);

			unset($row['klasseID']);
			unset($row['jahrgangID']);

			// TODO: Temporary unset. TO BE CLEARED
			unset($row['sprachenfolge']);
			unset($row['zp10']);
			unset($row['bkabschluss']);

			$schueler = Schueler::firstOrCreate(
				['id' => $row['id']],
				Arr::except(
					$row,
					['bemerkungen', 'lernabschnitt', 'leistungsdaten']
				)
			);

			$this->importLernabschnitte(schueler: $schueler, data: $row['lernabschnitt']);
			$this->importLeistungsdaten(schueler: $schueler, data: $row);


//			// Bemerkungen
//			if ($row['bemerkungen'] != null) {
//				dd($row['bemerkungen']);
//				$schueler->asv = $this->getBemerkung($row['bemerkungen'], 'asv');
//				$schueler->aue = $this->getBemerkung($row['bemerkungen'], 'aue');
//				$schueler->zb = $this->getBemerkung($row['bemerkungen'], 'zb');
//				$schueler->lels = $this->getBemerkung($row['bemerkungen'], 'lels');
//				$schueler->schulformEmpf = $this->getBemerkung($row['bemerkungen'], 'schulformEmpf');
//				$schueler->individuelleVersetzungsbemerkungen = $this->getBemerkung($row['bemerkungen'], 'individuelleVersetzungsbemerkungen');
//				$schueler->foerderbemerkungen = $this->getBemerkung($row['bemerkungen'], 'foerderbemerkungen');
//			}
//			$schueler->save();
//
//			$this->importSprachenfolge($schueler, $row);
//			$this->importZp10($schueler, $row);
//			$this->importBkAbschluss($schueler, $row);
		}

		$this->stop();
	}



	private function importLernabschnitte(Schueler $schueler, array $data): void
	{
		if ($data) { // TODO: To be updated if noteID is available // TODO: Idea to put into schuller

			$data['foerderschwerpunkt1'] = $this->getValueFromArray(
				data: $data,
				column: 'foerderschwerpunkt1',
				collection: $this->foerderschwerpunkte
			);

			$data['foerderschwerpunkt2'] = $this->getValueFromArray(
				data: $data,
				column: 'foerderschwerpunkt2',
				collection: $this->foerderschwerpunkte
			);

			$data['lernbereich1note'] = $this->getValueFromArray(
				data: $data,
				column: 'lernbereich1note',
				collection: $this->noten
			);

			$data['lernbereich2note'] = $this->getValueFromArray(
				data: $data,
				column: 'lernbereich2note',
				collection: $this->noten
			);

			$schueler->lernabschnitt()->create($data);
		}
	}

	private function importLeistungsdaten(Schueler $schueler, array $data): void
	{
		foreach($data['leistungsdaten'] as $row) {
			$row['lerngruppe_id'] = $row['lerngruppenID'];
			$row['note_id'] = $this->getValueFromArray(data: $row, column: 'note', collection: $this->noten);

			unset($row['lerngruppenID']);

			$schueler->leistungen()->firstOrCreate(
				['id' => $row['id']],
				Arr::except($row, ['teilleistungen', 'lerngruppenID', 'note'])
			);

// TODO: To be cleared how the structure ist
//				foreach($leistungsdaten['teilleistungen'] as $teilleistung) {
//					$leistung->teilleistungen()->create($teilleistung);
//				}


		}
	}

	private function start(string $text): void
	{
		$this->microtime = microtime(true);
		echo "  ! Importing $text \r\n";
	}

	private function stop(): void
	{
		$time = round(microtime(true) - $this->microtime, 2);
		echo "  ! Finished in $time \r\n";
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

