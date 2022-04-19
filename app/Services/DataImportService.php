<?php

namespace App\Services;

use App\Models\BKAbschluss;
use App\Models\BKFach;
use App\Models\Daten;
use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Foerderschwerpunkt;
use App\Models\Jahrgang;
use App\Models\Klasse;
use App\Models\Kurs;
use App\Models\Leistung;
use App\Models\Lernabschnitt;
use App\Models\Lerngruppe;
use App\Models\User as Lehrer;
use App\Models\Note;
use App\Models\Schueler;
use App\Models\Sprachenfolge;
use App\Models\Teilleistung;
use App\Models\Teilleistungsart;
use App\Models\Zp10;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataImportService
{
    private $json; 

    public function __construct(string $json)
    {
        $this->json = json_decode($json, true);
    }

    public function import(): void
    {
        $this->importDaten();

        $this->importWithExtId('faecher', Fach::class);
        $this->importWithExtId('jahrgaenge', Jahrgang::class);        
        $this->importWithExtId('teilleistungsarten', Teilleistungsart::class);
        $this->importDirect('foerderschwerpunkte', Foerderschwerpunkt::class);
        $this->importDirect('noten', Note::class);
        
        $this->importFloskelgruppen();
        $this->importKlassen();
        $this->importLerngruppen();
        $this->importSchueler();
    }

    private function importLerngruppen(): void
    {
        collect($this->json['lerngruppen'])->each(function (array $row) {
            $row['ext_id'] = $row['id'];

            $this->getRelation($row, Fach::class, 'fachID', 'fach_id');
 

            if ($row['kursartID']) {
                try {                    
                    $kurs = Kurs::firstOrCreate(['ext_id' => $row['kursartID']], ['kuerzel' => $row['kursartKuerzel']])->id;
                } catch (ModelNotFoundException $e) {
                    echo "Kurs existiert nicht. lehrerID: " . $row['kID'] ."\n\n";
                }                 

                $row['groupable_id'] = $kurs;
                $row['groupable_type'] = Kurs::class;
            } else {                
                try {                    
                    $klasse = Klasse::where('ext_id', $row['kID'])->firstOrFail()->id;
                } catch (ModelNotFoundException $e) {
                    echo "Klasse existiert nicht. lehrerID: " . $row['kID'] ."\n\n";
                }   

                
                $row['groupable_id'] = $klasse;
                $row['groupable_type'] = Klasse::class;
            }


            $lerngruppe = Lerngruppe::firstOrCreate(['ext_id' => $row['id']], Arr::except($row, ['lehrerID', 'fachID', 'kID', 'kursartKuerzel', 'user_id']));

            if ($row['lehrerID']) {
                foreach($row['lehrerID'] as $lehrer) {
                    try {                    
                        $lehrer = Lehrer::where('ext_id', $row['lehrerID'])->firstOrFail()->id;
                    } catch (ModelNotFoundException $e) {
                        echo "Lehrer existiert nicht. lehrerID: " . $row['lehrerID'] ."\n\n";
                    }
                    $lerngruppe->lehrer()->attach($lehrer);

                }
            }

        });
    }

    private function importSchueler(): void
    {
        collect($this->json['schueler'])->each(function (array $row) {
            $row['ext_id'] = $row['id'];

            $this->getRelation($row, Jahrgang::class, 'jahrgangID', 'jahrgang_id');

            
            // $this->getRelation($row, Klasse::class, 'klasseID', 'klasse_id'); // TODO: This cannot be found right now since all Schuelerklassen are 0. To be cleared with customer.
            try {                    
                $row['klasse_id'] = Klasse::first()->id;
            } catch (ModelNotFoundException $e) {
                echo "Schueler Klasse existiert nicht. klasseID: " . $row['klasseID'] ."\n\n";
            }      

            $schueler = Schueler::firstOrCreate(
                ['ext_id' => $row['id']], 
                Arr::only($row, ['ext_id', 'jahrgang_id', 'klasse_id', 'nachname', 'vorname', 'bilingualeSprache', 'istZieldifferent', 'istDaZFoerderung'])
            );

            if ($row['sprachenfolge']) {    
                $this->getRelation($row['sprachenfolge'], Note::class, 'fachID', 'fach_id');     
                Sprachenfolge::updateOrCreate(['schueler_id' => $schueler->id], Arr::except($row['sprachenfolge'], ['fachID']));  
            }
            
            if ($row['lernabschnitt']) {                                   
                $row['lernabschnitt']['ext_id'] = $row['lernabschnitt']['id'];           
                
                $this->getRelation($row['lernabschnitt'], Note::class, 'lernbereich1note', 'lernbereich1note', 'kuerzel');       
                $this->getRelation($row['lernabschnitt'], Note::class, 'lernbereich2note', 'lernbereich2note', 'kuerzel');   
                $this->getRelation($row['lernabschnitt'], Foerderschwerpunkt::class, 'foerderschwerpunkt1', 'foerderschwerpunkt1', 'kuerzel');   
                $this->getRelation($row['lernabschnitt'], Foerderschwerpunkt::class, 'foerderschwerpunkt2', 'foerderschwerpunkt2', 'kuerzel');  
                
                Lernabschnitt::updateOrCreate(['schueler_id' => $schueler->id], $row['lernabschnitt']);           
            }

            if ($row['bemerkungen']) { 
                collect($row['bemerkungen'])->each(fn (array $array) => $schueler->bemerkungen()->create($array)); 
            }
            

            if ($row['leistungsdaten']) { 
                foreach($row['leistungsdaten'] as $leistungsDaten) {             
                    $this->getRelation($leistungsDaten, Lerngruppe::class, 'lerngruppenID', 'lerngruppe_id'); 
                    $this->getRelation($leistungsDaten, Note::class, 'note', 'note_id'); 
                    
                    $leistung = Leistung::updateOrCreate(
                        ['schueler_id' => $schueler->id], 
                        Arr::except($leistungsDaten, ['lerngruppenID', 'note', 'teilleistungen'])
                    );  
                
                    if ($leistungsDaten['teilleistungen']) {
                        foreach ($leistungsDaten['teilleistungen'] as $teilleistungen) {
                            $teilleistungen['ext_id'] = $teilleistungen['id'];
                            $teilleistungen['leistung_id'] = $leistung->id;

                            $this->getRelation($teilleistungen, Teilleistungsart::class, 'artID', 'teilleistungsart_id');          
                            Teilleistung::updateOrCreate(
                                ['ext_id' => $teilleistungen['id']], 
                                Arr::except($teilleistungen, ['id', 'artID'])
                            );          
                        }
                    }
                }
            }

            if ($row['zp10']) {       
                $this->getRelation($row['zp10'], Fach::class, 'fachID', 'fach_id');
                $this->getRelation($row['zp10'], Note::class, 'vornote');
                $this->getRelation($row['zp10'], Note::class, 'noteSchriftlichePruefung');
                $this->getRelation($row['zp10'], Note::class, 'noteMuendlichePruefung');
                $this->getRelation($row['zp10'], Note::class, 'abschlussnote');                        
                        
                Zp10::updateOrCreate(['schueler_id' => $schueler->id], Arr::except($row['zp10'], ['fachID']));         
            }            

            if ($row['bkabschluss']) {             
                $this->getRelation($row['bkabschluss'], Note::class, 'notePraktischePruefung');                   
                $this->getRelation($row['noteKolloqium'], Note::class, 'noteKolloqium');                   
                $this->getRelation($row['noteFachpraxis'], Note::class, 'noteFachpraxis');                   
                $this->getRelation($row['noteFachpraxis'], Note::class, 'noteFachpraxis');                   
     
                $bkAbschluss = BKAbschluss::updateOrCreate(
                    ['schueler_id' => $schueler->id], 
                    Arr::except($row['bkabschluss'], ['notePraktischePruefung', 'noteKolloqium', 'noteFachpraxis', 'faecher'])
                );   
                
                if ($faecher = $row['bkabschluss']['faecher']) {
                    foreach($faecher as $fach) {
                        $this->getRelation($row, Fach::class, 'fachID', 'fach_id');     
                        $this->getRelation($row, Lehrer::class, 'lehrerID', 'lehrer_id');      
                        $this->getRelation($row, Note::class, 'vornote');   
                        $this->getRelation($row, Note::class, 'noteSchriftlichePruefung');   
                        $this->getRelation($row, Note::class, 'noteMuendlichePruefung');   
                        $this->getRelation($row, Note::class, 'noteBerufsabschluss');   
                        $this->getRelation($row, Note::class, 'abschlussnote');         

                        BKFach::updateOrCreate(
                            ['b_k_abschluss_id' => $bkAbschluss->id], 
                            Arr::except($fach, ['fachID', 'lehrerID'])
                        );   
                    }                
                } 
            }
        });
    }

    private function importKlassen(): void
    {

        collect($this->json['klassen'])->each(function (array $row) {
            $row['ext_id'] = $row['id'];
            $klasse = Klasse::firstOrCreate(['ext_id' => $row['id']], Arr::except($row, ['klassenlehrer']));

            foreach($row['klassenlehrer'] as $klassenlehrer) {             
                                 
                try {                    
                    $lehrer = Lehrer::where('ext_id', $klassenlehrer)->firstOrFail()->id;
                    $klasse->klassenlehrer()->attach($lehrer);
                } catch (ModelNotFoundException $e) {
                    echo "Klassenlehrer existiert nicht. LehrerID: " .  $klassenlehrer ."\n\n";
                }
            }
        });
    }


    private function importDaten(): void
    {
        $this->importLehrer();

        try { 
            Daten::updateOrCreate([
                'user_id' => Lehrer::where('ext_id', $this->json['lehrerID'])->firstOrFail()->id,
            ], [
                'enmRevision' => $this->json['enmRevision'],
                'schuljahr' => $this->json['schuljahr'],
                'anzahlAbschnitte' => $this->json['anzahlAbschnitte'],
                'aktuellerAbschnitt' => $this->json['aktuellerAbschnitt'],
                'publicKey' => $this->json['publicKey'],
                'fehlstundenEingabe' => $this->json['fehlstundenEingabe'],
                'fehlstundenSIFachbezogen' => $this->json['fehlstundenSIFachbezogen'],
                'fehlstundenSIIFachbezogen' => $this->json['fehlstundenSIIFachbezogen'],
                'schulform' => $this->json['schulform'],
                'mailadresse' => $this->json['mailadresse'],
            ]);
        } catch (ModelNotFoundException $e) {
            echo "Daten Lehrer existiert nicht. LehrerID: " . $this->json['lehrerID'] ."\n\n";
        }
    }

        
    private function importLehrer(): void
    {        
        collect($this->json['lehrer'])->each(function (array $row) {      
            $row['ext_id'] = $row['id'];

            Lehrer::updateOrCreate(
                ['ext_id' => $row['ext_id']], 
                [
                    'kuerzel' => $row['kuerzel'], 
                    'nachname' => $row['nachname'],
                    'vorname' => $row['vorname'],
                    'email' => Str::random() .'@'. Str::random(), // TODO: This has to be changed on api side, we have to have the emails delivered.
                    'password' => Str::random(),
                ],
            );
        });
    }

    private function importFloskelgruppen(): void
    {        
        collect($this->json['floskelgruppen'])->each(function (array $row) {
            $floskelgruppe = Floskelgruppe::firstOrCreate(['kuerzel' => $row['kuerzel']], Arr::except($row, ['floskeln']));

            foreach($row['floskeln'] as $floskel) {
                if ($floskel['fachID']) {
                    $this->getRelation($floskel, Fach::class, 'fachID', 'fach_id');   
                }

                if ($floskel['jahrgangID']) {
                    $this->getRelation($floskel, Jahrgang::class, 'jahrgangID', 'jahrgang_id');  
                }

                $floskelgruppe->floskeln()->create(Arr::except($floskel, ['fachID', 'jahrgangID']));
            }
        });
    }


    private function importDirect(string $table, $model, $key = 'kuerzel'): void
    {
        collect($this->json[$table])->each(fn (array $row) => $model::updateOrCreate([$key => $row[$key]], $row));
    }

    private function importWithExtId(string $table, string $model, string $source = 'id', string $destination = 'ext_id')
    {
        collect($this->json[$table])
            ->map(fn (array $row) => $this->map($row, $destination, $source))
            ->each(fn (array $row) => $model::updateOrCreate([$destination => $row[$destination]], Arr::except($row, $source)));
    }

    private function map(array $row, string $destination, string $source): string|array|null
    {
        $row[$destination] = $row[$source];

        return $row;    
    }    

    private function getRelation(array &$array, string $class, string $source, string|null $destination = null, string $primary = 'ext_id'): string|array|null
    {
        if ($array[$source]) {            
            $destination = $destination ?? $source;

            try {                    
                $array[$destination] = $class::where($primary, $array[$source])->firstOrFail()->id;                      
            } catch (ModelNotFoundException $e) {
                echo "{$class} existiert nicht. {$source}: {$array[$source]} \n\n";
                Log::warning($e->getMessage());
            }         
            
            return $array[$destination];
        } 
           
        return null;
    }
}

