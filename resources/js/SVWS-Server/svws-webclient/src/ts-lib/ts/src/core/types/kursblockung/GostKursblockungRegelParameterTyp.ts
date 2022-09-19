import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';

export class GostKursblockungRegelParameterTyp extends JavaObject {

	/** the name of the enumeration value */
	private readonly __name : String;

	/** the ordinal value for the enumeration value */
	private readonly __ordinal : number;

	/** an array containing all values of this enumeration */
	private static readonly all_values_by_ordinal : Array<GostKursblockungRegelParameterTyp> = [];

	/** an array containing all values of this enumeration indexed by their name*/
	private static readonly all_values_by_name : Map<String, GostKursblockungRegelParameterTyp> = new Map<String, GostKursblockungRegelParameterTyp>();

	public static readonly KURSART : GostKursblockungRegelParameterTyp = new GostKursblockungRegelParameterTyp("KURSART", 0, );

	public static readonly SCHIENEN_NR : GostKursblockungRegelParameterTyp = new GostKursblockungRegelParameterTyp("SCHIENEN_NR", 1, );

	public static readonly KURS_ID : GostKursblockungRegelParameterTyp = new GostKursblockungRegelParameterTyp("KURS_ID", 2, );

	public static readonly SCHUELER_ID : GostKursblockungRegelParameterTyp = new GostKursblockungRegelParameterTyp("SCHUELER_ID", 3, );

	private constructor(name : string, ordinal : number) {
		super();
		this.__name = name;
		this.__ordinal = ordinal;
		GostKursblockungRegelParameterTyp.all_values_by_ordinal.push(this);
		GostKursblockungRegelParameterTyp.all_values_by_name.set(name, this);
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
		if (!(other instanceof GostKursblockungRegelParameterTyp))
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
	public compareTo(other : GostKursblockungRegelParameterTyp) : number {
		return this.__ordinal - other.__ordinal;
	}

	/**
	 * Returns an array with enumeration values.
	 *
	 * @returns the array with enumeration values
	 */
	public static values() : Array<GostKursblockungRegelParameterTyp> {
		return [...this.all_values_by_ordinal];
	}

	/**
	 * Returns the enumeration value with the specified name.
	 *
	 * @param name   the name of the enumeration value
	 *
	 * @returns the enumeration values or null
	 */
	public static valueOf(name : String) : GostKursblockungRegelParameterTyp | null {
		let tmp : GostKursblockungRegelParameterTyp | undefined = this.all_values_by_name.get(name);
		return (!tmp) ? null : tmp;
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.types.kursblockung.GostKursblockungRegelParameterTyp'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_types_kursblockung_GostKursblockungRegelParameterTyp(obj : unknown) : GostKursblockungRegelParameterTyp {
	return obj as GostKursblockungRegelParameterTyp;
}
