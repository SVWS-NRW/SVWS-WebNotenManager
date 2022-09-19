import { JavaObject, cast_java_lang_Object } from '../../java/lang/JavaObject';
import { JavaInteger, cast_java_lang_Integer } from '../../java/lang/JavaInteger';
import { Comparable, cast_java_lang_Comparable } from '../../java/lang/Comparable';
import { JavaString, cast_java_lang_String } from '../../java/lang/JavaString';

export class SprachBelegungSekI extends JavaObject implements Comparable<SprachBelegungSekI | null> {

	public static readonly NICHT_BELEGT : SprachBelegungSekI = new SprachBelegungSekI(0);

	public static readonly MIND_2_JAHRE : SprachBelegungSekI = new SprachBelegungSekI(2);

	public static readonly MIND_4_JAHRE : SprachBelegungSekI = new SprachBelegungSekI(4);

	public static readonly AB_JAHRGANG_5 : SprachBelegungSekI = new SprachBelegungSekI(6);

	public readonly dauer : number;


	/**
	 * Erstellt einen neuen enum-Wert mit der angegebenen Dauer der Sprachbelegung.
	 * 
	 * @param dauer   die Dauer der Sprachbelegung in der Sek I
	 */
	private constructor(dauer : number) {
		super();
		this.dauer = dauer;
	}

	/**
	 * Ermittelt die Spachbelegung in der Sek I anhand des übergebenen Jahrgangs. 
	 * WICHTIG: Sollte ein Schüler sich im G8-Bildungsgang bewegen, so wird die Dauer 
	 * mit 6 Jahren hier nicht korrekt zugeordnet.  
	 * 
	 * @param ASDJahrgang der Jahrgang in welchem mit der Sprache begonnen wurde
	 * 
	 * @return die Sprachbelegung in der Sek I
	 */
	public static getByASDJahrgang(ASDJahrgang : String | null) : SprachBelegungSekI {
		if (ASDJahrgang === null) 
			return SprachBelegungSekI.NICHT_BELEGT;
		if (JavaString.compareTo(ASDJahrgang, "05") <= 0) 
			return SprachBelegungSekI.AB_JAHRGANG_5;
		if (JavaString.compareTo(ASDJahrgang, "07") <= 0) 
			return SprachBelegungSekI.MIND_4_JAHRE;
		if (JavaString.compareTo(ASDJahrgang, "09") <= 0) 
			return SprachBelegungSekI.MIND_2_JAHRE;
		return SprachBelegungSekI.NICHT_BELEGT;
	}

	/**
	 * Ermittelt die Spachbelegung in der Sek I anhand der übergebenen Dauer der Belegung in der Sek I.
	 * WICHTIG: Sollte ein Schüler sich im G8-Bildungsgang bewegen, so wird die Dauer 
	 * mit 6 Jahren hier nicht korrekt zugeordnet.  
	 *  
	 * @param dauer   die Dauer in vollen Jahren bei der Sprachbelegung in der Sek I
	 * 
	 * @return die Sprachbelegung in der Sek I
	 */
	public static getByDauer(dauer : number) : SprachBelegungSekI {
		if (dauer <= 0) 
			return SprachBelegungSekI.NICHT_BELEGT;
		if (dauer <= 3) 
			return SprachBelegungSekI.MIND_2_JAHRE;
		if (dauer <= 4) 
			return SprachBelegungSekI.MIND_4_JAHRE;
		return SprachBelegungSekI.AB_JAHRGANG_5;
	}

	public compareTo(other : SprachBelegungSekI | null) : number {
		if (other === null) 
			return 1;
		return JavaInteger.compare(this.dauer, other.dauer);
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['java.lang.Comparable', 'de.nrw.schule.svws.core.types.SprachBelegungSekI'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_types_SprachBelegungSekI(obj : unknown) : SprachBelegungSekI {
	return obj as SprachBelegungSekI;
}
