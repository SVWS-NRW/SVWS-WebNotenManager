import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { LehrerKatalogRechtsverhaeltnisEintrag, cast_de_nrw_schule_svws_core_data_lehrer_LehrerKatalogRechtsverhaeltnisEintrag } from '../../../core/data/lehrer/LehrerKatalogRechtsverhaeltnisEintrag';
import { HashMap, cast_java_util_HashMap } from '../../../java/util/HashMap';
import { JavaLong, cast_java_lang_Long } from '../../../java/lang/JavaLong';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';

export class LehrerRechtsverhaeltnis extends JavaObject {

	/** the name of the enumeration value */
	private readonly __name : String;

	/** the ordinal value for the enumeration value */
	private readonly __ordinal : number;

	/** an array containing all values of this enumeration */
	private static readonly all_values_by_ordinal : Array<LehrerRechtsverhaeltnis> = [];

	/** an array containing all values of this enumeration indexed by their name*/
	private static readonly all_values_by_name : Map<String, LehrerRechtsverhaeltnis> = new Map<String, LehrerRechtsverhaeltnis>();

	public static readonly L : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("L", 0, [new LehrerKatalogRechtsverhaeltnisEintrag(3, "L", "Beamter auf Lebenszeit", null, null)]);

	public static readonly P : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("P", 1, [new LehrerKatalogRechtsverhaeltnisEintrag(2, "P", "Beamter auf Probe", null, null)]);

	public static readonly A : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("A", 2, [new LehrerKatalogRechtsverhaeltnisEintrag(1, "A", "Beamter auf Probe zur Anstellung", null, null)]);

	public static readonly N : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("N", 3, [new LehrerKatalogRechtsverhaeltnisEintrag(7, "N", "Beamter, nebenamtlich (nicht hauptamtlich im Schuldienst)", null, null)]);

	public static readonly W : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("W", 4, [new LehrerKatalogRechtsverhaeltnisEintrag(9, "W", "Beamter auf Widerruf (LAA)", null, null)]);

	public static readonly U : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("U", 5, [new LehrerKatalogRechtsverhaeltnisEintrag(4, "U", "Angestellte, unbefristet (BAT-Vertrag)", null, null)]);

	public static readonly B : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("B", 6, [new LehrerKatalogRechtsverhaeltnisEintrag(5, "B", "Angestellte, befristet (BAT-Vertrag)", null, null)]);

	public static readonly J : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("J", 7, [new LehrerKatalogRechtsverhaeltnisEintrag(6, "J", "Angestellte, nicht BAT-Vertrag", null, null)]);

	public static readonly S : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("S", 8, [new LehrerKatalogRechtsverhaeltnisEintrag(8, "S", "Gestellungsvertrag", null, null)]);

	public static readonly X : LehrerRechtsverhaeltnis = new LehrerRechtsverhaeltnis("X", 9, [new LehrerKatalogRechtsverhaeltnisEintrag(10, "X", "Unentgeltlich Beschäftigte", null, null)]);

	public static VERSION : number = 1;

	public readonly daten : LehrerKatalogRechtsverhaeltnisEintrag;

	public readonly historie : Array<LehrerKatalogRechtsverhaeltnisEintrag>;

	private static readonly _rechtsverhaeltnisByID : HashMap<Number, LehrerRechtsverhaeltnis> = new HashMap();

	private static readonly _rechtsverhaeltnisByKuerzel : HashMap<String, LehrerRechtsverhaeltnis> = new HashMap();

	/**
	 * Erzeugt eine neue Art von Rechtsverhältnissen in der Aufzählung.
	 * 
	 * @param historie   die Historie des Rechtsverhältnisses, welches ein Array von {@link LehrerKatalogRechtsverhaeltnisEintrag} ist  
	 */
	private constructor(name : string, ordinal : number, historie : Array<LehrerKatalogRechtsverhaeltnisEintrag>) {
		super();
		this.__name = name;
		this.__ordinal = ordinal;
		LehrerRechtsverhaeltnis.all_values_by_ordinal.push(this);
		LehrerRechtsverhaeltnis.all_values_by_name.set(name, this);
		this.historie = historie;
		this.daten = historie[historie.length - 1];
	}

	/**
	 * Gibt eine Map von den IDs der Rechtsverhältnisse auf die zugehörigen Rechtsverhältnisse
	 * zurück. Sollte diese noch nicht initialisiert sein, so wird sie initielisiert.
	 *    
	 * @return die Map von den IDs der Rechtsverhältnisse auf die zugehörigen Rechtsverhältnisse
	 */
	private static getMapRechtsverhaeltnisByID() : HashMap<Number, LehrerRechtsverhaeltnis> {
		if (LehrerRechtsverhaeltnis._rechtsverhaeltnisByID.size() === 0) 
			for (let l of LehrerRechtsverhaeltnis.values()) 
				LehrerRechtsverhaeltnis._rechtsverhaeltnisByID.put(l.daten.id, l);
		return LehrerRechtsverhaeltnis._rechtsverhaeltnisByID;
	}

	/**
	 * Gibt eine Map von den Kürzeln der Rechtsverhältnisse auf die zugehörigen Rechtsverhältnisse
	 * zurück. Sollte diese noch nicht initialisiert sein, so wird sie initielisiert.
	 *    
	 * @return die Map von den Kürzeln der Rechtsverhältnisse auf die zugehörigen Rechtsverhältnisse
	 */
	private static getMapRechtsverhaeltnisByKuerzel() : HashMap<String, LehrerRechtsverhaeltnis> {
		if (LehrerRechtsverhaeltnis._rechtsverhaeltnisByKuerzel.size() === 0) 
			for (let l of LehrerRechtsverhaeltnis.values()) 
				LehrerRechtsverhaeltnis._rechtsverhaeltnisByKuerzel.put(l.daten.kuerzel, l);
		return LehrerRechtsverhaeltnis._rechtsverhaeltnisByKuerzel;
	}

	/**
	 * Gibt die Art von Rechtsverhältnissen anhand der angegebenen ID zurück.
	 * 
	 * @param id   die ID der Art von Rechtsverhältnissen
	 * 
	 * @return die Art von Rechtsverhältnissen oder null, falls die ID ungültig ist
	 */
	public static getByID(id : number) : LehrerRechtsverhaeltnis | null {
		return LehrerRechtsverhaeltnis.getMapRechtsverhaeltnisByID().get(id);
	}

	/**
	 * Gibt die Art von Rechtsverhältnissen anhand des angegebenen Kürzels zurück.
	 * 
	 * @param kuerzel   das Kürzel der Art von Rechtsverhältnissen
	 * 
	 * @return die Art von Rechtsverhältnissen oder null, falls das Kürzel ungültig ist
	 */
	public static getByKuerzel(kuerzel : String | null) : LehrerRechtsverhaeltnis | null {
		return LehrerRechtsverhaeltnis.getMapRechtsverhaeltnisByKuerzel().get(kuerzel);
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
		if (!(other instanceof LehrerRechtsverhaeltnis))
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
	public compareTo(other : LehrerRechtsverhaeltnis) : number {
		return this.__ordinal - other.__ordinal;
	}

	/**
	 * Returns an array with enumeration values.
	 *
	 * @returns the array with enumeration values
	 */
	public static values() : Array<LehrerRechtsverhaeltnis> {
		return [...this.all_values_by_ordinal];
	}

	/**
	 * Returns the enumeration value with the specified name.
	 *
	 * @param name   the name of the enumeration value
	 *
	 * @returns the enumeration values or null
	 */
	public static valueOf(name : String) : LehrerRechtsverhaeltnis | null {
		let tmp : LehrerRechtsverhaeltnis | undefined = this.all_values_by_name.get(name);
		return (!tmp) ? null : tmp;
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.types.statkue.LehrerRechtsverhaeltnis'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_types_statkue_LehrerRechtsverhaeltnis(obj : unknown) : LehrerRechtsverhaeltnis {
	return obj as LehrerRechtsverhaeltnis;
}