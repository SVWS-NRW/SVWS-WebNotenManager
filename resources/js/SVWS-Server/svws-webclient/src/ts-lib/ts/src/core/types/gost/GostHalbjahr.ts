import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaInteger, cast_java_lang_Integer } from '../../../java/lang/JavaInteger';
import { Comparable, cast_java_lang_Comparable } from '../../../java/lang/Comparable';
import { HashMap, cast_java_util_HashMap } from '../../../java/util/HashMap';
import { List, cast_java_util_List } from '../../../java/util/List';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';
import { Arrays, cast_java_util_Arrays } from '../../../java/util/Arrays';
import { IllegalArgumentException, cast_java_lang_IllegalArgumentException } from '../../../java/lang/IllegalArgumentException';

export class GostHalbjahr extends JavaObject implements Comparable<GostHalbjahr | null> {

	private static readonly map : HashMap<Number, GostHalbjahr> = new HashMap();

	private static readonly mapKuerzel : HashMap<String, GostHalbjahr> = new HashMap();

	private static readonly mapKuerzelAlt : HashMap<String, GostHalbjahr> = new HashMap();

	public static readonly maxHalbjahre : number = 6;

	public static readonly EF1 : GostHalbjahr = new GostHalbjahr(0, "EF", 1, "EF.1", "E1", "Einführungsphase 1. Halbjahr");

	public static readonly EF2 : GostHalbjahr = new GostHalbjahr(1, "EF", 2, "EF.2", "E2", "Einführungsphase 2. Halbjahr");

	public static readonly Q11 : GostHalbjahr = new GostHalbjahr(2, "Q1", 1, "Q1.1", "Q1", "Qualifikationsphase 1. Jahr, 1. Halbjahr");

	public static readonly Q12 : GostHalbjahr = new GostHalbjahr(3, "Q1", 2, "Q1.2", "Q2", "Qualifikationsphase 1. Jahr, 2. Halbjahr");

	public static readonly Q21 : GostHalbjahr = new GostHalbjahr(4, "Q2", 1, "Q2.1", "Q3", "Qualifikationsphase 2. Jahr, 1. Halbjahr");

	public static readonly Q22 : GostHalbjahr = new GostHalbjahr(5, "Q2", 2, "Q2.2", "Q4", "Qualifikationsphase 2. Jahr, 2. Halbjahr");

	public readonly id : number;

	public readonly jahrgang : String;

	public readonly halbjahr : number;

	public readonly kuerzel : String;

	public readonly kuerzelAlt : String;

	public readonly beschreibung : String;


	/**
	 * Erzeugt ein neues Halbjahr der Gymnasialen Oberstufe für diese Aufzählung.
	 * 
	 * @param id             die ID für das Halbjahr, welches die Reihenfolge der Halbjahre wiederspiegelt
	 * @param jahrgang       das Jahrgangskürzel des Halbjahres
	 * @param halbjahr       die Nummer des Halbjahres
	 * @param kuerzel        das eindeutige Kürzel für das Halbjahr der gymnasialen Oberstufe
	 * @param kuerzelAlt     ein eindeutiges Kürzel, welche in alten Tabellen verwendet wurde.
	 * @param beschreibung   die textuelle Beschreibung für das Halbjahr der gymnasialen Oberstufe
	 */
	private constructor(id : number, jahrgang : String, halbjahr : number, kuerzel : String, kuerzelAlt : String, beschreibung : String) {
		super();
		this.id = id;
		this.jahrgang = jahrgang;
		this.halbjahr = halbjahr;
		this.kuerzel = kuerzel;
		this.kuerzelAlt = kuerzelAlt;
		this.beschreibung = beschreibung;
		GostHalbjahr.map.put(id, this);
		GostHalbjahr.mapKuerzel.put(kuerzel, this);
		GostHalbjahr.mapKuerzelAlt.put(kuerzelAlt, this);
	}

	/**
	 * Gibt das nachfolgende Halbjahr zurück. 
	 * 
	 * @return das nachfolgende Halbjahr oder null, wenn es keines mehr gibt
	 */
	public next() : GostHalbjahr | null {
		return GostHalbjahr.map.get(this.id + 1);
	}

	/**
	 * Gibt das vorherige Halbjahr zurück. 
	 * 
	 * @return das vorherige Halbjahr oder null, wenn es keines mehr gibt
	 */
	public previous() : GostHalbjahr | null {
		return GostHalbjahr.map.get(this.id - 1);
	}

	/**
	 * Gibt alle Halbjahre zurück.
	 * 
	 * @return ein Array mit allen Halbjahren der gymnasialen Oberstufe
	 */
	public static values() : Array<GostHalbjahr> {
		let alle : Array<GostHalbjahr> = [GostHalbjahr.EF1, GostHalbjahr.EF2, GostHalbjahr.Q11, GostHalbjahr.Q12, GostHalbjahr.Q21, GostHalbjahr.Q22];
		return alle;
	}

	/**
	 * Gibt alle Halbjahre der Einführungsphase zurück.
	 * 
	 * @return ein Array mit allen Halbjahren der Einführungsphase der gymnasialen Oberstufe
	 */
	public static getEinfuehrungsphase() : Array<GostHalbjahr> {
		let ef : Array<GostHalbjahr> = [GostHalbjahr.EF1, GostHalbjahr.EF2];
		return ef;
	}

	/**
	 * Gibt alle Halbjahre der Qualifikationsphase zurück.
	 * 
	 * @return ein Array mit allen Halbjahren der Qualifikationsphase der gymnasialen Oberstufe
	 */
	public static getQualifikationsphase() : Array<GostHalbjahr> {
		let q : Array<GostHalbjahr> = [GostHalbjahr.Q11, GostHalbjahr.Q12, GostHalbjahr.Q21, GostHalbjahr.Q22];
		return q;
	}

	/**
	 * Gibt alle Halbjahre des übergebenen Jahrgangs zurück.
	 * 
	 * @param jahrgang     der Jahrgang
	 * 
	 * @return ein Array mit den Halbjahren des Jahrgangs
	 */
	public static getHalbjahreFromJahrgang(jahrgang : String) : Array<GostHalbjahr> {
		switch (jahrgang) {
			case "EF": 
				let ef : Array<GostHalbjahr> = [GostHalbjahr.EF1, GostHalbjahr.EF2]
				return ef;
			case "Q1": 
				let q1 : Array<GostHalbjahr> = [GostHalbjahr.Q11, GostHalbjahr.Q12]
				return q1;
			case "Q2": 
				let q2 : Array<GostHalbjahr> = [GostHalbjahr.Q21, GostHalbjahr.Q22]
				return q2;
		}
		throw new IllegalArgumentException("Der angegebene Jahrgang ist kein gültiger Jahrgang der gymnasialen Oberstufe")
	}

	/**
	 * Gibt das Halbjahr zurück, welches die übergebene ID hat.
	 * 
	 * @param id   die ID des Halbjahres
	 * 
	 * @return das Halbjahr oder null, falls die ID nicht gültig ist
	 */
	public static fromID(id : Number | null) : GostHalbjahr | null {
		if (id === null) 
			return null;
		switch (id) {
			case 0: 
				return GostHalbjahr.EF1;
			case 1: 
				return GostHalbjahr.EF2;
			case 2: 
				return GostHalbjahr.Q11;
			case 3: 
				return GostHalbjahr.Q12;
			case 4: 
				return GostHalbjahr.Q21;
			case 5: 
				return GostHalbjahr.Q22;
		}
		return null;
	}

	/**
	 * Gibt das Halbjahr zurück, welches das übergebene Kürzel hat.
	 * 
	 * @param kuerzel   das Kürzel
	 * 
	 * @return das Halbjahr oder null, falls das Kürzel nicht gültig ist
	 */
	public static fromKuerzel(kuerzel : String | null) : GostHalbjahr | null {
		return GostHalbjahr.mapKuerzel.get(kuerzel);
	}

	/**
	 * Gibt das Halbjahr zurück, welches das übergebene alte Kürzel hat.
	 * 
	 * @param kuerzelAlt   das alte Kürzel
	 * 
	 * @return das Halbjahr oder null, falls das Kürzel nicht gültig ist
	 */
	public static fromKuerzelAlt(kuerzelAlt : String | null) : GostHalbjahr | null {
		return GostHalbjahr.mapKuerzelAlt.get(kuerzelAlt);
	}

	/**
	 * Gibt das Halbjahr zurück, welches zu dem übergebenen Jahrgang und Halbjahr gehört.
	 * 
	 * @param jahrgang     der Jahrgang
	 * @param halbjahr     die Nummer des Halbjahres
	 * 
	 * @return das Halbjahr oder null, falls es kein gültiges Halbjahr mit den Angaben gibt.
	 */
	public static fromJahrgangUndHalbjahr(jahrgang : String | null, halbjahr : number) : GostHalbjahr | null {
		if ((halbjahr !== 1) && (halbjahr !== 2)) 
			return null;
		switch (jahrgang) {
			case "EF": 
				return (halbjahr === 1) ? GostHalbjahr.EF1 : GostHalbjahr.EF2;
			case "Q1": 
				return (halbjahr === 1) ? GostHalbjahr.Q11 : GostHalbjahr.Q12;
			case "Q2": 
				return (halbjahr === 1) ? GostHalbjahr.Q21 : GostHalbjahr.Q22;
		}
		return null;
	}

	/**
	 * Ermittelt das Halbjahr der gymnasialen Oberstufe anhand des angegegebenen Abiturjahres und
	 * dem aktuellen Schuljahr und Halbjahr
	 * 
	 * @param abiturjahr   das Abiturjahr
	 * @param schuljahr    das aktuelle Schuljahr
	 * @param halbjahr     das aktuelle Halbjahr
	 * 
	 * @return das Halbjahr der gymnasialen Oberstufe oder null
	 */
	public static fromAbiturjahrSchuljahrUndHalbjahr(abiturjahr : number, schuljahr : number, halbjahr : number) : GostHalbjahr | null {
		let id : number = ((schuljahr + 3 - abiturjahr) * 2) + halbjahr - 1;
		return GostHalbjahr.fromID(id);
	}

	/**
	 * Bestimmt das Halbjahr der gymnasialen Oberstufe anhand des angegegebenen Abiturjahres und
	 * dem aktuellen Schuljahr und Halbjahr und gibt anhand dessen das Halbjahr der gymnasialen 
	 * Oberstufe zurück, welches als nächstes geplant wird.
	 * 
	 * @param abiturjahr   das Abiturjahr
	 * @param schuljahr    das aktuelle Schuljahr
	 * @param halbjahr     das aktuelle Halbjahr
	 * 
	 * @return das nächste Halbjahr der gymnasialen Oberstufe zur Planung oder null, wenn der 
	 *         Jahrgang in der Q2.2 ist oder das Abitur bereits abgeschlossen ist.
	 */
	public static getPlanungshalbjahrFromAbiturjahrSchuljahrUndHalbjahr(abiturjahr : number, schuljahr : number, halbjahr : number) : GostHalbjahr | null {
		let id : number = ((schuljahr + 3 - abiturjahr) * 2) + halbjahr;
		if (id < 0) 
			id = 0;
		return GostHalbjahr.fromID(id);
	}

	/**
	 * Gibt zurück, ob es Einführungsphase ist.
	 * 
	 * @return  Einführungsphase, true oder false
	 */
	public istEinfuehrungsphase() : boolean {
		return JavaObject.equalsTranspiler("EF", (this.jahrgang));
	}

	/**
	 * Gibt zurück, ob es Qualifikationsphase ist.
	 * 
	 * @return  Qualifikationsphase, true oder false
	 */
	public istQualifikationsphase() : boolean {
		return !this.istEinfuehrungsphase();
	}

	/**
	 * Prüft anhand der übergebenen Halbjahre, ob es sich um die beiden Halbjahre
	 * der Einführungsphase handelt.
	 * 
	 * @param halbjahre    die Halbjahre
	 * 
	 * @return true, wenn es sich um die beiden Halbjahre der Einführungsphase handelt
	 *         und ansonsten false
	 */
	public static pruefeEinfuehrungsphase(...halbjahre : Array<GostHalbjahr>) : boolean {
		if ((halbjahre === null) || (halbjahre.length !== 2)) 
			return false;
		return ((halbjahre[0] as unknown === GostHalbjahr.EF1 as unknown) && (halbjahre[0] as unknown === GostHalbjahr.EF2 as unknown)) || ((halbjahre[0] as unknown === GostHalbjahr.EF2 as unknown) && (halbjahre[0] as unknown === GostHalbjahr.EF1 as unknown));
	}

	/**
	 * Prüft anhand der übergebenen Halbjahre, ob es sich um die vier Halbjahre
	 * der Qualifikationsphase handelt.
	 * 
	 * @param halbjahre    die Halbjahre
	 * 
	 * @return true, wenn es sich um die vier Halbjahre der Qualifikationsphase 
	 *         handelt und ansonsten false
	 */
	public static pruefeQualifikationsphase(...halbjahre : Array<GostHalbjahr>) : boolean {
		if ((halbjahre === null) || (halbjahre.length !== 4)) 
			return false;
		let list : List<GostHalbjahr> = Arrays.asList(...halbjahre);
		return (list.contains(GostHalbjahr.Q11) && list.contains(GostHalbjahr.Q12) && list.contains(GostHalbjahr.Q21) && list.contains(GostHalbjahr.Q22));
	}

	public compareTo(other : GostHalbjahr | null) : number {
		if (other === null) 
			return -1;
		return JavaInteger.compare(this.id, other.id);
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['java.lang.Comparable', 'de.nrw.schule.svws.core.types.gost.GostHalbjahr'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_types_gost_GostHalbjahr(obj : unknown) : GostHalbjahr {
	return obj as GostHalbjahr;
}