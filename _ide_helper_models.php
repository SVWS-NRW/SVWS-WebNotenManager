<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\BKAbschluss
 *
 * @property int $id
 * @property int $schueler_id
 * @property int $hatZulassung
 * @property int $hatBestanden
 * @property int $hatZulassungErweiterteBeruflicheKenntnisse
 * @property int $hatErworbenErweiterteBeruflicheKenntnisse
 * @property \App\Models\Note|null $notePraktischePruefung
 * @property \App\Models\Note|null $noteKolloqium
 * @property int $hatZulassungBerufsabschlusspruefung
 * @property int $hatBestandenBerufsabschlusspruefung
 * @property string $themaAbschlussarbeit
 * @property int $istVorhandenBerufsabschlusspruefung
 * @property \App\Models\Note|null $noteFachpraxis
 * @property int $istFachpraktischerTeilAusreichend
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schueler|null $schuler
 * @method static \Database\Factories\BKAbschlussFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss query()
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatBestanden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatBestandenBerufsabschlusspruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatErworbenErweiterteBeruflicheKenntnisse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatZulassung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatZulassungBerufsabschlusspruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereHatZulassungErweiterteBeruflicheKenntnisse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereIstFachpraktischerTeilAusreichend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereIstVorhandenBerufsabschlusspruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereNoteFachpraxis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereNoteKolloqium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereNotePraktischePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereThemaAbschlussarbeit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKAbschluss whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BKFach[] $bkFaecher
 * @property-read int|null $bk_faecher_count
 * @property-read \App\Models\Schueler|null $schueler
 */
	class BKAbschluss extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BKFach
 *
 * @property int $id
 * @property int $b_k_abschluss_id
 * @property int $fach_id
 * @property int $user_id
 * @property int $istSchriftlich
 * @property \App\Models\Note|null $vornote
 * @property \App\Models\Note|null $noteSchriftlichePruefung
 * @property int $muendlichePruefung
 * @property int $muendlichePruefungFreiwillig
 * @property \App\Models\Note|null $noteMuendlichePruefung
 * @property int $istSchriftlichBerufsabschluss
 * @property \App\Models\Note|null $noteBerufsabschluss
 * @property \App\Models\Note|null $abschlussnote
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BKAbschluss|null $bkabschluss
 * @property-read \App\Models\Fach|null $fach
 * @property-read Lehrer|null $lehrer
 * @method static \Database\Factories\BKFachFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach query()
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereAbschlussnote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereBKAbschlussId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereIstSchriftlich($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereIstSchriftlichBerufsabschluss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereMuendlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereMuendlichePruefungFreiwillig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereNoteBerufsabschluss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereNoteMuendlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereNoteSchriftlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BKFach whereVornote($value)
 * @mixin \Eloquent
 */
	class BKFach extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Bemerkung
 *
 * @property int $id
 * @property int $schueler_id
 * @property string|null $asv
 * @property string $aue
 * @property string|null $zb
 * @property string|null $lels
 * @property string|null $schulformEmpf
 * @property string|null $individuelleVersetzungsbemerkungen
 * @property string|null $foerderbemerkungen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schueler|null $schuler
 * @method static \Database\Factories\BemerkungFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereAsv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereAue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereFoerderbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereIndividuelleVersetzungsbemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereLels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereSchulformEmpf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bemerkung whereZb($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Schueler|null $schueler
 */
	class Bemerkung extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Daten
 *
 * @property int $id
 * @property int $enmRevision
 * @property int $schuljahr
 * @property int $anzahlAbschnitte
 * @property int $aktuellerAbschnitt
 * @property string|null $publicKey
 * @property int $user_id
 * @property int $fehlstundenEingabe
 * @property int $fehlstundenSIFachbezogen
 * @property int $fehlstundenSIIFachbezogen
 * @property string $schulform
 * @property string|null $mailadresse
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Lehrer|null $lehrer
 * @method static \Database\Factories\DatenFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Daten newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Daten query()
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereAktuellerAbschnitt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereAnzahlAbschnitte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereEnmRevision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereFehlstundenEingabe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereFehlstundenSIFachbezogen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereFehlstundenSIIFachbezogen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereMailadresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereSchulform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereSchuljahr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Schueler[] $schueler
 * @property-read int|null $schueler_count
 * @property int $lehrerID API LehrerID value
 * @method static \Illuminate\Database\Eloquent\Builder|Daten whereLehrerID($value)
 */
	class Daten extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Fach
 *
 * @property int $id
 * @property int $ext_id
 * @property string $kuerzel
 * @property string $kuerzelAnzeige
 * @property int $sortierung
 * @property int $istFremdsprache
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\FachFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fach newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fach query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereIstFremdsprache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereKuerzelAnzeige($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fach whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Fach extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Floskel
 *
 * @property int $id
 * @property int $floskelgruppe_id
 * @property string $kuerzel
 * @property string $text
 * @property int|null $fach_id
 * @property int|null $niveau
 * @property int|null $jahrgang_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fach|null $fach
 * @property-read \App\Models\Floskelgruppe|null $floskelgruppe
 * @property-read \App\Models\Jahrgang|null $jahrgang
 * @method static \Database\Factories\FloskelFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereFloskelgruppeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereJahrgangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereNiveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Floskel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Floskelgruppe
 *
 * @property int $id
 * @property string $kuerzel
 * @property string $bezeichnung
 * @property string $hauptgruppe
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Floskel[] $floskeln
 * @property-read int|null $floskeln_count
 * @method static \Database\Factories\FloskelgruppeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereBezeichnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereHauptgruppe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Floskelgruppe whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Floskelgruppe extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Foerderschwerpunkt
 *
 * @property int $id
 * @property string $kuerzel
 * @property string $beschreibung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\FoerderschwerpunktFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereBeschreibung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Foerderschwerpunkt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Foerderschwerpunkt extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Jahrgang
 *
 * @property int $id
 * @property int $ext_id
 * @property string $kuerzel
 * @property string $kuerzelAnzeige
 * @property string $beschreibung
 * @property string $stufe
 * @property int|null $sortierung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\JahrgangFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereBeschreibung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereKuerzelAnzeige($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereStufe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jahrgang whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Jahrgang extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Klasse
 *
 * @property int $id
 * @property int $ext_id
 * @property string $kuerzel
 * @property string $kuerzelAnzeige
 * @property int|null $sortierung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Lehrer[] $klassenlehrer
 * @property-read int|null $klassenlehrer_count
 * @method static \Database\Factories\KlasseFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereKuerzelAnzeige($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Klasse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Klasse extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Kurs
 *
 * @property int $id
 * @property int $ext_id
 * @property string $kuerzel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lerngruppe[] $lerngruppen
 * @property-read int|null $lerngruppen_count
 * @method static \Database\Factories\KursFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $bezeichnung
 * @method static \Illuminate\Database\Eloquent\Builder|Kurs whereBezeichnung($value)
 */
	class Kurs extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Leistung
 *
 * @property int $id
 * @property int $schueler_id
 * @property int $lerngruppe_id
 * @property int|null $note_id
 * @property int $istSchriftlich
 * @property string|null $abiturfach
 * @property int|null $fehlstundenGesamt
 * @property int|null $fehlstundenUnentschuldigt
 * @property string|null $fachbezogeneBemerkungen
 * @property string|null $neueZuweisungKursart
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lerngruppe|null $lerngruppe
 * @property-read \App\Models\Note|null $note
 * @property-read \App\Models\Schueler|null $schueler
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Teilleistung[] $teilleistungen
 * @property-read int|null $teilleistungen_count
 * @method static \Database\Factories\LeistungFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung query()
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereAbiturfach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereFachbezogeneBemerkungen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereFehlstundenGesamt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereFehlstundenUnentschuldigt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereIstSchriftlich($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereLerngruppeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereNeueZuweisungKursart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $ext_id
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereExtId($value)
 * @property int $istGemahnt
 * @property string|null $mahndatum
 * @property-read \App\Models\LeistungNormalized|null $leistungNormalized
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereIstGemahnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leistung whereMahndatum($value)
 */
	class Leistung extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LeistungNormalized
 *
 * @property int $id
 * @property int $leistung_id
 * @property string|null $klasse
 * @property string $vorname
 * @property string $nachname
 * @property string $geschlecht
 * @property string $jahrgang
 * @property string|null $fach
 * @property string $lehrer
 * @property string|null $kurs
 * @property string|null $note
 * @property bool $istGemahnt
 * @property int $fs
 * @property int $ufs
 * @property bool $asv
 * @property bool $aue
 * @property bool $zb
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Leistung|null $leistung
 * @method static \Database\Factories\LeistungNormalizedFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereAsv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereAue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereFach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereFs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereGeschlecht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereIstGemahnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereJahrgang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereKlasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereKurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereLehrer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereLeistungId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereNachname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereUfs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereVorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeistungNormalized whereZb($value)
 */
	class LeistungNormalized extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Lernabschnitt
 *
 * @property int $id
 * @property int $ext_id
 * @property int $schueler_id
 * @property string $pruefungsordnung
 * @property \App\Models\Note|null $lernbereich1note
 * @property \App\Models\Note|null $lernbereich2note
 * @property \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt1
 * @property \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schueler|null $schueler
 * @method static \Database\Factories\LernabschnittFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFoerderschwerpunkt1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereFoerderschwerpunkt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereLernbereich1note($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereLernbereich2note($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt wherePruefungsordnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lernabschnitt whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Note|null $lernbereich1Note
 * @property-read \App\Models\Note|null $lernbereich2Note
 * @property-read \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt1Relation
 * @property-read \App\Models\Foerderschwerpunkt|null $foerderschwerpunkt2Relation
 */
	class Lernabschnitt extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Lerngruppe
 *
 * @property int $id
 * @property int $ext_id
 * @property int $groupable_id
 * @property string $groupable_type
 * @property int $fach_id
 * @property string|null $kursartID
 * @property string $bezeichnung
 * @property string|null $bilingualeSprache
 * @property int $wochenstunden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $groupable
 * @property-read \Illuminate\Database\Eloquent\Collection|Lehrer[] $lehrer
 * @property-read int|null $lehrer_count
 * @method static \Database\Factories\LerngruppeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereBezeichnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereBilingualeSprache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereGroupableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereGroupableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereKursartID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lerngruppe whereWochenstunden($value)
 * @mixin \Eloquent
 * @property-read Fach|null $fach
 */
	class Lerngruppe extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Note
 *
 * @property int $id
 * @property string $kuerzel
 * @property int|null $notenpunkte
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\NoteFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereNotenpunkte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Note extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Schueler
 *
 * @property int $id
 * @property int $daten_id
 * @property int $ext_id
 * @property int $jahrgang_id
 * @property int $klasse_id
 * @property string $nachname
 * @property string $vorname
 * @property string $geschlecht
 * @property \App\Models\Fach|null $bilingualeSprache
 * @property int $istZieldifferent
 * @property int $istDaZFoerderung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bemerkung|null $bemerkung
 * @property-read \App\Models\BKAbschluss|null $bkabschluss
 * @property-read \App\Models\Daten|null $daten
 * @property-read \App\Models\Jahrgang|null $jahrgang
 * @property-read \App\Models\Klasse|null $klasse
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Leistung[] $leistungen
 * @property-read int|null $leistungen_count
 * @property-read \App\Models\Lernabschnitt|null $lernabschnitt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sprachenfolge[] $sprachenfolgen
 * @property-read int|null $sprachenfolgen_count
 * @property-read \App\Models\Zp10|null $zp10
 * @method static \Database\Factories\SchuelerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler query()
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereBilingualeSprache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereDatenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereGeschlecht($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereIstDaZFoerderung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereIstZieldifferent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereJahrgangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereKlasseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereNachname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schueler whereVorname($value)
 * @mixin \Eloquent
 */
	class Schueler extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Sprachenfolge
 *
 * @property int $id
 * @property int $schueler_id
 * @property int $fach_id
 * @property int $reihenfolge
 * @property int|null $belegungVonJahrgang
 * @property int|null $belegungVonAbschnitt
 * @property int|null $belegungBisJahrgang
 * @property int|null $belegungBisAbschnitt
 * @property string|null $referenzniveau
 * @property int|null $belegungSekI
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fach|null $fach
 * @property-read \App\Models\Schueler|null $schueler
 * @method static \Database\Factories\SprachenfolgeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungBisAbschnitt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungBisJahrgang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungSekI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungVonAbschnitt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereBelegungVonJahrgang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereReferenzniveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereReihenfolge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprachenfolge whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Sprachenfolge extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Teilleistung
 *
 * @property int $id
 * @property int $leistung_id
 * @property int $teilleistungsart_id
 * @property string|null $datum
 * @property string|null $bemerkung
 * @property int|null $note_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Leistung|null $leistung
 * @property-read \App\Models\Teilleistungsart|null $teilleistungsart
 * @method static \Database\Factories\TeilleistungFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung query()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereBemerkung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereDatum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereLeistungId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereTeilleistungsartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $ext_id
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistung whereExtId($value)
 */
	class Teilleistung extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Teilleistungsart
 *
 * @property int $id
 * @property int $ext_id
 * @property string $bezeichnung
 * @property int|null $sortierung
 * @property float|null $gewichtung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Teilleistung[] $teilleistungen
 * @property-read int|null $teilleistungen_count
 * @method static \Database\Factories\TeilleistungsartFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereBezeichnung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereGewichtung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereSortierung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teilleistungsart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Teilleistungsart extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $ext_id
 * @property string $kuerzel
 * @property string $nachname
 * @property string|null $vorname
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lerngruppe[] $lerngruppen
 * @property-read int|null $lerngruppen_count
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKuerzel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNachname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVorname($value)
 * @property-read \App\Models\Daten|null $daten
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Zp10
 *
 * @property int $id
 * @property int $schueler_id
 * @property int $fach_id
 * @property \App\Models\Note|null $vornote
 * @property \App\Models\Note|null $noteSchriftlichePruefung
 * @property int $muendlichePruefung
 * @property int $muendlichePruefungFreiwillig
 * @property \App\Models\Note|null $noteMuendlichePruefung
 * @property \App\Models\Note|null $abschlussnote
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fach|null $fach
 * @property-read \App\Models\Schueler|null $schueler
 * @method static \Database\Factories\Zp10Factory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 query()
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereAbschlussnote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereFachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereMuendlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereMuendlichePruefungFreiwillig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereNoteMuendlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereNoteSchriftlichePruefung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereSchuelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Zp10 whereVornote($value)
 * @mixin \Eloquent
 */
	class Zp10 extends \Eloquent {}
}

