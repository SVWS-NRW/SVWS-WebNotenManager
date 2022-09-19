import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { HashMap, cast_java_util_HashMap } from '../../../java/util/HashMap';
import { SchulformKatalogEintrag, cast_de_nrw_schule_svws_core_data_schule_SchulformKatalogEintrag } from '../../../core/data/schule/SchulformKatalogEintrag';
import { List, cast_java_util_List } from '../../../java/util/List';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';
import { Vector, cast_java_util_Vector } from '../../../java/util/Vector';

export class Schulform extends JavaObject {

	/** the name of the enumeration value */
	private readonly __name : String;

	/** the ordinal value for the enumeration value */
	private readonly __ordinal : number;

	/** an array containing all values of this enumeration */
	private static readonly all_values_by_ordinal : Array<Schulform> = [];

	/** an array containing all values of this enumeration indexed by their name*/
	private static readonly all_values_by_name : Map<String, Schulform> = new Map<String, Schulform>();

	public static readonly BK : Schulform = new Schulform("BK", 0, [new SchulformKatalogEintrag(1000, "BK", "30", "Berufskolleg", false, null, null)]);

	public static readonly FW : Schulform = new Schulform("FW", 1, [new SchulformKatalogEintrag(2000, "FW", "17", "Freie Waldorfschule", true, null, null)]);

	public static readonly G : Schulform = new Schulform("G", 2, [new SchulformKatalogEintrag(3000, "G", "02", "Grundschule", false, null, null)]);

	public static readonly GE : Schulform = new Schulform("GE", 3, [new SchulformKatalogEintrag(4000, "GE", "15", "Gesamtschule", true, null, null)]);

	public static readonly GM : Schulform = new Schulform("GM", 4, [new SchulformKatalogEintrag(5000, "GM", "16", "Gemeinschaftsschule", false, null, null)]);

	public static readonly GY : Schulform = new Schulform("GY", 5, [new SchulformKatalogEintrag(6000, "GY", "20", "Gymnasium", true, null, null)]);

	public static readonly H : Schulform = new Schulform("H", 6, [new SchulformKatalogEintrag(7000, "H", "04", "Hauptschule", false, null, null)]);

	public static readonly HI : Schulform = new Schulform("HI", 7, [new SchulformKatalogEintrag(8000, "HI", "18", "Hibernia", false, null, null)]);

	public static readonly PS : Schulform = new Schulform("PS", 8, [new SchulformKatalogEintrag(9000, "PS", "13", "Schulversuch PRIMUS", false, null, null)]);

	public static readonly R : Schulform = new Schulform("R", 9, [new SchulformKatalogEintrag(10000, "R", "10", "Realschule", false, null, null)]);

	public static readonly S : Schulform = new Schulform("S", 10, [new SchulformKatalogEintrag(11000, "S", "08", "Förderschule im Bereich G/H", false, null, null)]);

	public static readonly KS : Schulform = new Schulform("KS", 11, [new SchulformKatalogEintrag(12000, "KS", "83", "Klinikschule", false, null, null)]);

	public static readonly SB : Schulform = new Schulform("SB", 12, [new SchulformKatalogEintrag(13000, "SB", "88", "Förderschule im Bereich Berufskolleg", false, null, null)]);

	public static readonly SG : Schulform = new Schulform("SG", 13, [new SchulformKatalogEintrag(14000, "SG", "87", "Förderschule im Bereich Gymnasium", true, null, null)]);

	public static readonly SK : Schulform = new Schulform("SK", 14, [new SchulformKatalogEintrag(15000, "SK", "14", "Sekundarschule", false, null, null)]);

	public static readonly SR : Schulform = new Schulform("SR", 15, [new SchulformKatalogEintrag(16000, "SR", "85", "Förderschule im Bereich Realschule", false, null, null)]);

	public static readonly V : Schulform = new Schulform("V", 16, [new SchulformKatalogEintrag(17000, "V", "06", "nicht umorganisierte Volksschule", false, null, null)]);

	public static readonly WB : Schulform = new Schulform("WB", 17, [new SchulformKatalogEintrag(18000, "WB", "25", "Weiterbildungskolleg", false, null, null)]);

	public static readonly WF : Schulform = new Schulform("WF", 18, [new SchulformKatalogEintrag(19000, "WF", "19", "Freie Waldorfschule (Förderschule)", true, null, null)]);

	public static readonly AS : Schulform = new Schulform("AS", 19, [new SchulformKatalogEintrag(100000, "AS", null, "Ausländische Schüler, die zugewandert sind", false, null, null)]);

	public static readonly HU : Schulform = new Schulform("HU", 20, [new SchulformKatalogEintrag(200000, "HU", null, "Hochschule, Universität", false, null, null)]);

	public static readonly LB : Schulform = new Schulform("LB", 21, [new SchulformKatalogEintrag(300000, "LB", null, "Förderschule, Schwerpunkt Lernen", false, null, null)]);

	public static readonly SO : Schulform = new Schulform("SO", 22, [new SchulformKatalogEintrag(400000, "SO", null, "Sonstige Förderschulen", true, null, null)]);

	public static readonly SP : Schulform = new Schulform("SP", 23, [new SchulformKatalogEintrag(500000, "SP", null, "Ausgesiedelte Schüler, die zugewandert sind", false, null, null)]);

	public static readonly WZ : Schulform = new Schulform("WZ", 24, [new SchulformKatalogEintrag(600000, "WZ", null, "Wehrdienst bzw. Zivildienst", false, null, null)]);

	public static readonly XB : Schulform = new Schulform("XB", 25, [new SchulformKatalogEintrag(700000, "XB", null, "Berufstätigkeit, z.B. vor Besuch einer Fachschule", false, null, null)]);

	public static readonly XS : Schulform = new Schulform("XS", 26, [new SchulformKatalogEintrag(800000, "XS", null, "Sonstige Schulen bzw. keine Schule", false, null, null)]);

	public static VERSION : number = 1;

	public readonly daten : SchulformKatalogEintrag;

	public readonly historie : Array<SchulformKatalogEintrag>;

	private static readonly _schulformen : HashMap<String, Schulform> = new HashMap();

	private static readonly _schulformenNummer : HashMap<String, Schulform> = new HashMap();

	/**
	 * Erzeugt eine neue Schulform in der Aufzählung.
	 * 
	 * @param historie   die Historie der Schulformen, welches ein Array von {@link SchulformKatalogEintrag} ist  
	 */
	private constructor(name : string, ordinal : number, historie : Array<SchulformKatalogEintrag>) {
		super();
		this.__name = name;
		this.__ordinal = ordinal;
		Schulform.all_values_by_ordinal.push(this);
		Schulform.all_values_by_name.set(name, this);
		this.historie = historie;
		this.daten = historie[historie.length - 1];
	}

	/**
	 * Gibt eine Map von den Kürzels der Schulformen auf die zugehörigen Schulformen
	 * zurück. Sollte diese noch nicht initialisiert sein, so wird sie initielisiert.
	 *    
	 * @return die Map von den Kürzels der Schulformen auf die zugehörigen Schulformen
	 */
	private static getMapSchulformenByKuerzel() : HashMap<String, Schulform> {
		if (Schulform._schulformen.size() === 0) {
			for (let s of Schulform.values()) {
				if (s.daten !== null) 
					Schulform._schulformen.put(s.daten.kuerzel, s);
			}
		}
		return Schulform._schulformen;
	}

	/**
	 * Gibt eine Map von den Kürzels der Schulformen auf die zugehörigen Schulformen
	 * zurück. Sollte diese noch nicht initialisiert sein, so wird sie initielisiert.
	 *    
	 * @return die Map von den Kürzels der Schulformen auf die zugehörigen Schulformen
	 */
	private static getMapSchulformenByNummer() : HashMap<String, Schulform> {
		if (Schulform._schulformenNummer.size() === 0) 
			for (let s of Schulform.values()) 
				if ((s.daten !== null) && (s.daten.nummer !== null)) 
					Schulform._schulformenNummer.put(s.daten.nummer, s);
		return Schulform._schulformenNummer;
	}

	/**
	 * Gibt die Schulform für das angegebene Kürzel zurück.
	 * 
	 * @param kuerzel   das Kürzel der Schulform
	 * 
	 * @return die Schulform oder null, falls das Kürzel ungültig ist
	 */
	public static getByKuerzel(kuerzel : String | null) : Schulform | null {
		return Schulform.getMapSchulformenByKuerzel().get(kuerzel);
	}

	/**
	 * Gibt die Schulform für die angegebene Nummer zurück.
	 * 
	 * @param nummer   die Nummer der Schulform
	 * 
	 * @return die Schulform oder null, falls keine Schulform mit dieser Nummer vorhanden ist
	 */
	public static getByNummer(nummer : String | null) : Schulform | null {
		return Schulform.getMapSchulformenByNummer().get(nummer);
	}

	/**
	 * Gibt alle "echten" Schulformen dieser Aufzählung zurück.
	 * Das bedeutet, dass Pseudoschulformen, die in NRW nicht 
	 * existieren, nicht zurückgegeben werden.
	 * 
	 * @return eine {@link List} mit alle "echten" Schulformen
	 */
	public static get() : List<Schulform> {
		let result : Vector<Schulform> = new Vector();
		for (let sf of Schulform.values()) 
			if ((sf.daten !== null) && (sf.daten.nummer !== null)) 
				result.add(sf);
		return result;
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
		if (!(other instanceof Schulform))
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
	public compareTo(other : Schulform) : number {
		return this.__ordinal - other.__ordinal;
	}

	/**
	 * Returns an array with enumeration values.
	 *
	 * @returns the array with enumeration values
	 */
	public static values() : Array<Schulform> {
		return [...this.all_values_by_ordinal];
	}

	/**
	 * Returns the enumeration value with the specified name.
	 *
	 * @param name   the name of the enumeration value
	 *
	 * @returns the enumeration values or null
	 */
	public static valueOf(name : String) : Schulform | null {
		let tmp : Schulform | undefined = this.all_values_by_name.get(name);
		return (!tmp) ? null : tmp;
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.types.statkue.Schulform'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_types_statkue_Schulform(obj : unknown) : Schulform {
	return obj as Schulform;
}
