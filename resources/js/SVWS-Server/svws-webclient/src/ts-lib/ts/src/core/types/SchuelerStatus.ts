import { JavaObject, cast_java_lang_Object } from '../../java/lang/JavaObject';
import { JavaInteger, cast_java_lang_Integer } from '../../java/lang/JavaInteger';
import { HashMap, cast_java_util_HashMap } from '../../java/util/HashMap';
import { JavaString, cast_java_lang_String } from '../../java/lang/JavaString';

export class SchuelerStatus extends JavaObject {

	/** the name of the enumeration value */
	private readonly __name : String;

	/** the ordinal value for the enumeration value */
	private readonly __ordinal : number;

	/** an array containing all values of this enumeration */
	private static readonly all_values_by_ordinal : Array<SchuelerStatus> = [];

	/** an array containing all values of this enumeration indexed by their name*/
	private static readonly all_values_by_name : Map<String, SchuelerStatus> = new Map<String, SchuelerStatus>();

	public static readonly NEUAUFNAHME : SchuelerStatus = new SchuelerStatus("NEUAUFNAHME", 0, 0, "Neuaufnahme", null, null);

	public static readonly WARTELISTE : SchuelerStatus = new SchuelerStatus("WARTELISTE", 1, 1, "Warteliste", null, null);

	public static readonly AKTIV : SchuelerStatus = new SchuelerStatus("AKTIV", 2, 2, "Aktiv", null, null);

	public static readonly BEURLAUBT : SchuelerStatus = new SchuelerStatus("BEURLAUBT", 3, 3, "Beurlaubt", null, null);

	public static readonly EXTERN : SchuelerStatus = new SchuelerStatus("EXTERN", 4, 6, "Extern", null, null);

	public static readonly ABSCHLUSS : SchuelerStatus = new SchuelerStatus("ABSCHLUSS", 5, 8, "Abschluss", null, null);

	public static readonly ABGANG : SchuelerStatus = new SchuelerStatus("ABGANG", 6, 9, "Abgang", null, null);

	public static readonly EHEMALIGE : SchuelerStatus = new SchuelerStatus("EHEMALIGE", 7, 10, "Ehemalige", null, null);

	public static VERSION : number = 1;

	private static readonly _mapID : HashMap<Number, SchuelerStatus> = new HashMap();

	private static readonly _mapBezeichnungen : HashMap<String, SchuelerStatus> = new HashMap();

	public readonly id : number;

	public readonly bezeichnung : String;

	public readonly gueltigVon : Number | null;

	public readonly gueltigBis : Number | null;

	/**
	 * Erzeugt einen neuen Schüler-Status in der Aufzählung.
	 * 
	 * @param id            die ID des Schüler Status, welche auch in der SVWS-Datenbank genutzt wird
	 * @param bezeichnung   die textuelle Bezeichnung des Schülerstatus
	 * @param gueltigVon    gibt an, in welchem Schuljahr der Schülerstatus einführt wurde. Ist kein Schuljahr bekannt, so ist null gesetzt.
	 * @param gueltigBis    gibt an, bis zu welchem Schuljahr der Schülerstatus gültig ist. Ist kein Schuljahr bekannt, so ist null gesetzt.
	 */
	private constructor(name : string, ordinal : number, id : number, bezeichnung : String, gueltigVon : Number | null, gueltigBis : Number | null) {
		super();
		this.__name = name;
		this.__ordinal = ordinal;
		SchuelerStatus.all_values_by_ordinal.push(this);
		SchuelerStatus.all_values_by_name.set(name, this);
		this.id = id;
		this.bezeichnung = bezeichnung;
		this.gueltigVon = gueltigVon;
		this.gueltigBis = gueltigBis;
	}

	/**
	 * Gibt eine Map von den IDs der Schüler-Status auf die zugehörigen Schüler-Status
	 * zurück. Sollte diese noch nicht initialisiert sein, so wird sie initielisiert.
	 *    
	 * @return die Map von den IDs der Schüler-Status auf die zugehörigen Schüler-Status
	 */
	private static getMapID() : HashMap<Number, SchuelerStatus> {
		if (SchuelerStatus._mapID.size() === 0) 
			for (let p of SchuelerStatus.values()) 
				SchuelerStatus._mapID.put(p.id, p);
		return SchuelerStatus._mapID;
	}

	/**
	 * Gibt eine Map von den Bezeichnungen der Schüler-Status auf die zugehörigen Schüler-Status
	 * zurück. Sollte diese noch nicht initialisiert sein, so wird sie initielisiert.
	 *    
	 * @return die Map von den Bezeichnungen der Schüler-Status auf die zugehörigen Schüler-Status
	 */
	private static getMapBezeichnungen() : HashMap<String, SchuelerStatus> {
		if (SchuelerStatus._mapBezeichnungen.size() === 0) 
			for (let p of SchuelerStatus.values()) 
				SchuelerStatus._mapBezeichnungen.put(p.bezeichnung.toUpperCase(), p);
		return SchuelerStatus._mapBezeichnungen;
	}

	/**
	 *
	 * Gibt den Schülerstatus anhand der ID zurück.
	 * 
	 * @param status	die id des Schülerstatus
	 * 
	 * @return der Schülerstatus oder null, falls die ID ungültig ist
	 * 
	 */
	public static fromID(status : number) : SchuelerStatus | null {
		return SchuelerStatus.getMapID().get(status);
	}

	/**
	 *
	 * Ermittelt den Schülerstatus anhand der Bezeichnung.
	 * 
	 * @param value	  die Bezeichnung des Schülerstatus
	 * 
	 * @return der Schülerstatus oder null, falls die Bezeichnung ungültig ist
	 * 
	 */
	public static fromBezeichnung(value : String | null) : SchuelerStatus | null {
		if (value === null) 
			return null;
		return SchuelerStatus.getMapBezeichnungen().get(value.toUpperCase());
	}

	/**
	 * Prüft, ob die übergebene ID für einen gültigen Schülerstatus
	 * steht oder nicht
	 * 
	 * @param id   die zu prüfende ID
	 * 
	 * @return true, falls die ID gültig ist.
	 */
	public static isValidID(id : Number | null) : boolean {
		if (id === null) 
			return false;
		for (let status of SchuelerStatus.values()) 
			if (status.id === id) 
				return true;
		return false;
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
		if (!(other instanceof SchuelerStatus))
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
	public compareTo(other : SchuelerStatus) : number {
		return this.__ordinal - other.__ordinal;
	}

	/**
	 * Returns an array with enumeration values.
	 *
	 * @returns the array with enumeration values
	 */
	public static values() : Array<SchuelerStatus> {
		return [...this.all_values_by_ordinal];
	}

	/**
	 * Returns the enumeration value with the specified name.
	 *
	 * @param name   the name of the enumeration value
	 *
	 * @returns the enumeration values or null
	 */
	public static valueOf(name : String) : SchuelerStatus | null {
		let tmp : SchuelerStatus | undefined = this.all_values_by_name.get(name);
		return (!tmp) ? null : tmp;
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.types.SchuelerStatus'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_types_SchuelerStatus(obj : unknown) : SchuelerStatus {
	return obj as SchuelerStatus;
}