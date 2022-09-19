import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { BenutzerKompetenzKatalogEintrag, cast_de_nrw_schule_svws_core_data_benutzer_BenutzerKompetenzKatalogEintrag } from '../../../core/data/benutzer/BenutzerKompetenzKatalogEintrag';
import { HashMap, cast_java_util_HashMap } from '../../../java/util/HashMap';
import { JavaLong, cast_java_lang_Long } from '../../../java/lang/JavaLong';
import { BenutzerKompetenzGruppe, cast_de_nrw_schule_svws_core_types_benutzer_BenutzerKompetenzGruppe } from '../../../core/types/benutzer/BenutzerKompetenzGruppe';

export class BenutzerKompetenz extends JavaObject {

	/** the name of the enumeration value */
	private readonly __name : String;

	/** the ordinal value for the enumeration value */
	private readonly __ordinal : number;

	/** an array containing all values of this enumeration */
	private static readonly all_values_by_ordinal : Array<BenutzerKompetenz> = [];

	/** an array containing all values of this enumeration indexed by their name*/
	private static readonly all_values_by_name : Map<String, BenutzerKompetenz> = new Map<String, BenutzerKompetenz>();

	public static readonly KEINE : BenutzerKompetenz = new BenutzerKompetenz("KEINE", 0, new BenutzerKompetenzKatalogEintrag(-2, BenutzerKompetenzGruppe.KEINE, "keine"));

	public static readonly ADMIN : BenutzerKompetenz = new BenutzerKompetenz("ADMIN", 1, new BenutzerKompetenzKatalogEintrag(-1, BenutzerKompetenzGruppe.ADMIN, "admin"));

	public static readonly SCHUELER_INDIVIDUALDATEN_ANSEHEN : BenutzerKompetenz = new BenutzerKompetenz("SCHUELER_INDIVIDUALDATEN_ANSEHEN", 2, new BenutzerKompetenzKatalogEintrag(11, BenutzerKompetenzGruppe.SCHUELER_INDIVIDUALDATEN, "Ansehen"));

	public static readonly SCHUELER_INDIVIDUALDATEN_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("SCHUELER_INDIVIDUALDATEN_AENDERN", 3, new BenutzerKompetenzKatalogEintrag(12, BenutzerKompetenzGruppe.SCHUELER_INDIVIDUALDATEN, "Ändern"));

	public static readonly SCHUELER_INDIVIDUALDATEN_LOESCHEN : BenutzerKompetenz = new BenutzerKompetenz("SCHUELER_INDIVIDUALDATEN_LOESCHEN", 4, new BenutzerKompetenzKatalogEintrag(13, BenutzerKompetenzGruppe.SCHUELER_INDIVIDUALDATEN, "Löschen"));

	public static readonly SCHUELER_INDIVIDUALDATEN_VERMERKE_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("SCHUELER_INDIVIDUALDATEN_VERMERKE_AENDERN", 5, new BenutzerKompetenzKatalogEintrag(14, BenutzerKompetenzGruppe.SCHUELER_INDIVIDUALDATEN, "Vermerke ändern"));

	public static readonly SCHUELER_INDIVIDUALDATEN_KAOA_DATEN_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("SCHUELER_INDIVIDUALDATEN_KAOA_DATEN_AENDERN", 6, new BenutzerKompetenzKatalogEintrag(15, BenutzerKompetenzGruppe.SCHUELER_INDIVIDUALDATEN, "KAoA-Daten ändern"));

	public static readonly SCHUELER_INDIVIDUALDATEN_EINWILLIGUNGEN_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("SCHUELER_INDIVIDUALDATEN_EINWILLIGUNGEN_AENDERN", 7, new BenutzerKompetenzKatalogEintrag(16, BenutzerKompetenzGruppe.SCHUELER_INDIVIDUALDATEN, "Einwilligungen ändern (DSGVO, Lernplattformen)"));

	public static readonly SCHUELER_LEISTUNGSDATEN_ANSEHEN : BenutzerKompetenz = new BenutzerKompetenz("SCHUELER_LEISTUNGSDATEN_ANSEHEN", 8, new BenutzerKompetenzKatalogEintrag(21, BenutzerKompetenzGruppe.SCHUELER_LEISTUNGSDATEN, "Ansehen"));

	public static readonly SCHUELER_LEISTUNGSDATEN_FUNKTIONSBEZOGEN_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("SCHUELER_LEISTUNGSDATEN_FUNKTIONSBEZOGEN_AENDERN", 9, new BenutzerKompetenzKatalogEintrag(22, BenutzerKompetenzGruppe.SCHUELER_LEISTUNGSDATEN, "Funktionsbezogen ändern"));

	public static readonly SCHUELER_LEISTUNGSDATEN_ALLE_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("SCHUELER_LEISTUNGSDATEN_ALLE_AENDERN", 10, new BenutzerKompetenzKatalogEintrag(23, BenutzerKompetenzGruppe.SCHUELER_LEISTUNGSDATEN, "Alle ändern"));

	public static readonly BERICHTE_ALLE_FORMULARE_DRUCKEN : BenutzerKompetenz = new BenutzerKompetenz("BERICHTE_ALLE_FORMULARE_DRUCKEN", 11, new BenutzerKompetenzKatalogEintrag(31, BenutzerKompetenzGruppe.BERICHTE, "Alle Formulare drucken"));

	public static readonly BERICHTE_STANDARDFORMULARE_DRUCKEN : BenutzerKompetenz = new BenutzerKompetenz("BERICHTE_STANDARDFORMULARE_DRUCKEN", 12, new BenutzerKompetenzKatalogEintrag(32, BenutzerKompetenzGruppe.BERICHTE, "Standard-Formulare drucken"));

	public static readonly BERICHTE_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("BERICHTE_AENDERN", 13, new BenutzerKompetenzKatalogEintrag(33, BenutzerKompetenzGruppe.BERICHTE, "Ändern"));

	public static readonly BERICHTE_LOESCHEN : BenutzerKompetenz = new BenutzerKompetenz("BERICHTE_LOESCHEN", 14, new BenutzerKompetenzKatalogEintrag(34, BenutzerKompetenzGruppe.BERICHTE, "Löschen"));

	public static readonly IMPORT_EXPORT_DATEN_IMPORTIEREN : BenutzerKompetenz = new BenutzerKompetenz("IMPORT_EXPORT_DATEN_IMPORTIEREN", 15, new BenutzerKompetenzKatalogEintrag(41, BenutzerKompetenzGruppe.IMPORT_EXPORT, "Daten importieren"));

	public static readonly IMPORT_EXPORT_SCHUELERDATEN_EXPORTIEREN : BenutzerKompetenz = new BenutzerKompetenz("IMPORT_EXPORT_SCHUELERDATEN_EXPORTIEREN", 16, new BenutzerKompetenzKatalogEintrag(42, BenutzerKompetenzGruppe.IMPORT_EXPORT, "Schülerdaten exportieren"));

	public static readonly IMPORT_EXPORT_LEHRERDATEN_EXPORTIEREN : BenutzerKompetenz = new BenutzerKompetenz("IMPORT_EXPORT_LEHRERDATEN_EXPORTIEREN", 17, new BenutzerKompetenzKatalogEintrag(43, BenutzerKompetenzGruppe.IMPORT_EXPORT, "Lehrerdaten exportieren"));

	public static readonly IMPORT_EXPORT_SCHNITTSTELLE_SCHILD_NRW : BenutzerKompetenz = new BenutzerKompetenz("IMPORT_EXPORT_SCHNITTSTELLE_SCHILD_NRW", 18, new BenutzerKompetenzKatalogEintrag(44, BenutzerKompetenzGruppe.IMPORT_EXPORT, "Schnittstelle SchILD-NRW verwenden"));

	public static readonly IMPORT_EXPORT_ACCESS_DB : BenutzerKompetenz = new BenutzerKompetenz("IMPORT_EXPORT_ACCESS_DB", 19, new BenutzerKompetenzKatalogEintrag(45, BenutzerKompetenzGruppe.IMPORT_EXPORT, "Access-DB-Export durchführen"));

	public static readonly IMPORT_EXPORT_XML : BenutzerKompetenz = new BenutzerKompetenz("IMPORT_EXPORT_XML", 20, new BenutzerKompetenzKatalogEintrag(46, BenutzerKompetenzGruppe.IMPORT_EXPORT, "Export über XML-Schnittstellen"));

	public static readonly SCHULBEZOGENE_DATEN_ANSEHEN : BenutzerKompetenz = new BenutzerKompetenz("SCHULBEZOGENE_DATEN_ANSEHEN", 21, new BenutzerKompetenzKatalogEintrag(61, BenutzerKompetenzGruppe.SCHULBEZOGENE_DATEN, "Ansehen"));

	public static readonly SCHULBEZOGENE_DATEN_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("SCHULBEZOGENE_DATEN_AENDERN", 22, new BenutzerKompetenzKatalogEintrag(62, BenutzerKompetenzGruppe.SCHULBEZOGENE_DATEN, "Ändern"));

	public static readonly EXTRAS_BACKUP_DURCHFUEHREN : BenutzerKompetenz = new BenutzerKompetenz("EXTRAS_BACKUP_DURCHFUEHREN", 23, new BenutzerKompetenzKatalogEintrag(71, BenutzerKompetenzGruppe.EXTRAS, "Backup durchführen"));

	public static readonly EXTRAS_GELOESCHTE_DATEN_ZURUECKHOLEN : BenutzerKompetenz = new BenutzerKompetenz("EXTRAS_GELOESCHTE_DATEN_ZURUECKHOLEN", 24, new BenutzerKompetenzKatalogEintrag(72, BenutzerKompetenzGruppe.EXTRAS, "Gelöschte Daten zurückholen"));

	public static readonly EXTRAS_FARBEN_FUER_FACHGRUPPEN_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("EXTRAS_FARBEN_FUER_FACHGRUPPEN_AENDERN", 25, new BenutzerKompetenzKatalogEintrag(73, BenutzerKompetenzGruppe.EXTRAS, "Farben für Fachgruppen ändern"));

	public static readonly EXTRAS_DATEN_AUS_KURS42_IMPORTIEREN : BenutzerKompetenz = new BenutzerKompetenz("EXTRAS_DATEN_AUS_KURS42_IMPORTIEREN", 26, new BenutzerKompetenzKatalogEintrag(74, BenutzerKompetenzGruppe.EXTRAS, "Daten aus Kurs42 importieren"));

	public static readonly EXTRAS_DATEN_PERSONENGRUPPEN_BEARBEITEN : BenutzerKompetenz = new BenutzerKompetenz("EXTRAS_DATEN_PERSONENGRUPPEN_BEARBEITEN", 27, new BenutzerKompetenzKatalogEintrag(75, BenutzerKompetenzGruppe.EXTRAS, "Personengruppen bearbeiten"));

	public static readonly KATALOG_EINTRAEGE_ANSEHEN : BenutzerKompetenz = new BenutzerKompetenz("KATALOG_EINTRAEGE_ANSEHEN", 28, new BenutzerKompetenzKatalogEintrag(81, BenutzerKompetenzGruppe.KATALOG_EINTRAEGE, "Ansehen"));

	public static readonly KATALOG_EINTRAEGE_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("KATALOG_EINTRAEGE_AENDERN", 29, new BenutzerKompetenzKatalogEintrag(82, BenutzerKompetenzGruppe.KATALOG_EINTRAEGE, "Ändern"));

	public static readonly KATALOG_EINTRAEGE_LOESCHEN : BenutzerKompetenz = new BenutzerKompetenz("KATALOG_EINTRAEGE_LOESCHEN", 30, new BenutzerKompetenzKatalogEintrag(83, BenutzerKompetenzGruppe.KATALOG_EINTRAEGE, "Löschen"));

	public static readonly LEHRERDATEN_ANSEHEN : BenutzerKompetenz = new BenutzerKompetenz("LEHRERDATEN_ANSEHEN", 31, new BenutzerKompetenzKatalogEintrag(91, BenutzerKompetenzGruppe.LEHRERDATEN, "Ansehen"));

	public static readonly LEHRERDATEN_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("LEHRERDATEN_AENDERN", 32, new BenutzerKompetenzKatalogEintrag(92, BenutzerKompetenzGruppe.LEHRERDATEN, "Ändern"));

	public static readonly LEHRERDATEN_LOESCHEN : BenutzerKompetenz = new BenutzerKompetenz("LEHRERDATEN_LOESCHEN", 33, new BenutzerKompetenzKatalogEintrag(93, BenutzerKompetenzGruppe.LEHRERDATEN, "Löschen"));

	public static readonly LEHRERDATEN_DETAILDATEN_ANSEHEN : BenutzerKompetenz = new BenutzerKompetenz("LEHRERDATEN_DETAILDATEN_ANSEHEN", 34, new BenutzerKompetenzKatalogEintrag(94, BenutzerKompetenzGruppe.LEHRERDATEN, "Detaildaten ansehen"));

	public static readonly LEHRERDATEN_DETAILDATEN_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("LEHRERDATEN_DETAILDATEN_AENDERN", 35, new BenutzerKompetenzKatalogEintrag(95, BenutzerKompetenzGruppe.LEHRERDATEN, "Detaildaten ändern"));

	public static readonly SCHULPFLICHTVERLETZUNG_ANSEHEN : BenutzerKompetenz = new BenutzerKompetenz("SCHULPFLICHTVERLETZUNG_ANSEHEN", 36, new BenutzerKompetenzKatalogEintrag(101, BenutzerKompetenzGruppe.SCHULPFLICHTVERLETZUNG, "Ansehen"));

	public static readonly SCHULPFLICHTVERLETZUNG_AENDERN : BenutzerKompetenz = new BenutzerKompetenz("SCHULPFLICHTVERLETZUNG_AENDERN", 37, new BenutzerKompetenzKatalogEintrag(102, BenutzerKompetenzGruppe.SCHULPFLICHTVERLETZUNG, "Ändern"));

	public static readonly SCHULPFLICHTVERLETZUNG_LOESCHEN : BenutzerKompetenz = new BenutzerKompetenz("SCHULPFLICHTVERLETZUNG_LOESCHEN", 38, new BenutzerKompetenzKatalogEintrag(103, BenutzerKompetenzGruppe.SCHULPFLICHTVERLETZUNG, "Löschen"));

	public static readonly ADRESSDATEN_ERZIEHER_ANSEHEN : BenutzerKompetenz = new BenutzerKompetenz("ADRESSDATEN_ERZIEHER_ANSEHEN", 39, new BenutzerKompetenzKatalogEintrag(201, BenutzerKompetenzGruppe.CARDDAV, "Ansehen"));

	public static readonly ADRESSDATEN_ANSEHEN : BenutzerKompetenz = new BenutzerKompetenz("ADRESSDATEN_ANSEHEN", 40, new BenutzerKompetenzKatalogEintrag(202, BenutzerKompetenzGruppe.CARDDAV, "Ansehen"));

	public static VERSION : number = 1;

	public readonly daten : BenutzerKompetenzKatalogEintrag;

	private static readonly _mapID : HashMap<Number, BenutzerKompetenz> = new HashMap();

	/**
	 * Erzeugt eine neue Benutzerkompetenz für die Aufzählung.
	 *
	 * @param id                  die ID der Benutzerkompetenz
	 * @param bezeichnungGruppe   die Bezeichnung der Gruppe von Kompetenzen zu dieser diese Benutzerkompetenz gehört
	 * @param bezeichnung         die Bezeichnung der Benutzerkompetenz
	 */
	private constructor(name : string, ordinal : number, daten : BenutzerKompetenzKatalogEintrag) {
		super();
		this.__name = name;
		this.__ordinal = ordinal;
		BenutzerKompetenz.all_values_by_ordinal.push(this);
		BenutzerKompetenz.all_values_by_name.set(name, this);
		this.daten = daten;
	}

	/**
	 * Gibt eine Map von den IDs der Benutzerkompetenzen auf die zugehörigen Benutzerkompetenzen
	 * zurück. Sollte diese noch nicht initialisiert sein, so wird sie initielisiert.
	 *    
	 * @return die Map von den IDs der Benutzerkompetenzen auf die zugehörigen Benutzerkompetenzen
	 */
	private static getMapID() : HashMap<Number, BenutzerKompetenz> {
		if (BenutzerKompetenz._mapID.size() === 0) 
			for (let p of BenutzerKompetenz.values()) 
				BenutzerKompetenz._mapID.put(p.daten.id, p);
		return BenutzerKompetenz._mapID;
	}

	/**
	 *
	 * Gibt die Benutzerkompetenz anhand der übergebenen ID zurück. 
	 * 
	 * @param id    die ID der Benutzerkompetenz
	 *  
	 * @return die Benutzerkompetenz oder null, falls die ID fehlerhaft ist
	 */
	public static getByID(id : number) : BenutzerKompetenz | null {
		return BenutzerKompetenz.getMapID().get(id);
	}

	/**
	 * Returns the name of this enumeration value.
	 *
	 * @returns the name
	 */
	private name() : String {
		return this.__name;
	}

	/**
	 * Returns the ordinal value of this enumeration value.
	 *
	 * @returns the ordinal value
	 */
	private ordinal() : number {
		return this.__ordinal;
	}

	/**
	 * Returns the name of this enumeration value.
	 *
	 * @returns the name
	 */
	public toString() : String {
		return this.__name;
	}

	/**
	 * Returns true if this and the other enumeration values are equal.
	 *
	 * @param other   the other enumeration value
	 *
	 * @returns true if they are equal and false otherwise
	 */
	public equals(other : JavaObject) : boolean {
		if (!(other instanceof BenutzerKompetenz))
			return false;
		return this === other;
	}

	/**
	 * Returns the ordinal value as hashcode, since the ordinal value is unique.
	 *
	 * @returns the ordinal value as hashcode
	 */
	public hashCode() : number {
		return this.__ordinal;
	}

	/**
	 * Compares this enumeration value with the other enumeration value by their ordinal value.
	 *
	 * @param other   the other enumeration value
	 *
	 * @returns a negative, zero or postive value as this enumeration value is less than, equal to
	 *          or greater than the other enumeration value
	 */
	public compareTo(other : BenutzerKompetenz) : number {
		return this.__ordinal - other.__ordinal;
	}

	/**
	 * Returns an array with enumeration values.
	 *
	 * @returns the array with enumeration values
	 */
	public static values() : Array<BenutzerKompetenz> {
		return [...this.all_values_by_ordinal];
	}

	/**
	 * Returns the enumeration value with the specified name.
	 *
	 * @param name   the name of the enumeration value
	 *
	 * @returns the enumeration values or null
	 */
	public static valueOf(name : String) : BenutzerKompetenz | null {
		let tmp : BenutzerKompetenz | undefined = this.all_values_by_name.get(name);
		return (!tmp) ? null : tmp;
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.types.benutzer.BenutzerKompetenz'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_types_benutzer_BenutzerKompetenz(obj : unknown) : BenutzerKompetenz {
	return obj as BenutzerKompetenz;
}
