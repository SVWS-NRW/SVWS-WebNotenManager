import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { Schulform, cast_de_nrw_schule_svws_core_types_statkue_Schulform } from '../../../core/types/statkue/Schulform';
import { Schulgliederung, cast_de_nrw_schule_svws_core_types_statkue_Schulgliederung } from '../../../core/types/statkue/Schulgliederung';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';

export class SchulformSchulgliederung extends JavaObject {

	public schulform : String = "GY";

	public gliederung : String | null = null;


	/**
	 * Erstellt ein Objekt mit Standardwerten
	 */
	public constructor();

	/**
	 * Erstellt ein Objekt mit den angegebenen Werten
	 * 
	 * @param schulform      die Schulform
	 * @param gliederung     die Schulgliederung oder null 
	 */
	public constructor(schulform : Schulform, gliederung : Schulgliederung | null);

	/**
	 * Implementation for method overloads of 'constructor'
	 */
	public constructor(__param0? : Schulform, __param1? : Schulgliederung | null) {
		super();
		if ((typeof __param0 === "undefined") && (typeof __param1 === "undefined")) {
			} else if (((typeof __param0 !== "undefined") && ((__param0 instanceof JavaObject) && (__param0.isTranspiledInstanceOf('de.nrw.schule.svws.core.types.statkue.Schulform')))) && ((typeof __param1 !== "undefined") && ((__param1 instanceof JavaObject) && (__param1.isTranspiledInstanceOf('de.nrw.schule.svws.core.types.statkue.Schulgliederung'))) || (__param1 === null))) {
			let schulform : Schulform = cast_de_nrw_schule_svws_core_types_statkue_Schulform(__param0);
			let gliederung : Schulgliederung | null = cast_de_nrw_schule_svws_core_types_statkue_Schulgliederung(__param1);
			this.schulform = schulform.daten.kuerzel;
			this.gliederung = (gliederung === null) ? null : gliederung.daten.kuerzel;
		} else throw new Error('invalid method overload');
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.schule.SchulformSchulgliederung'].includes(name);
	}

	public static transpilerFromJSON(json : string): SchulformSchulgliederung {
		const obj = JSON.parse(json);
		const result = new SchulformSchulgliederung();
		if (typeof obj.schulform === "undefined")
			 throw new Error('invalid json format, missing attribute schulform');
		result.schulform = obj.schulform;
		result.gliederung = typeof obj.gliederung === "undefined" ? null : obj.gliederung;
		return result;
	}

	public static transpilerToJSON(obj : SchulformSchulgliederung) : string {
		let result = '{';
		result += '"schulform" : ' + '"' + obj.schulform.valueOf() + '"' + ',';
		result += '"gliederung" : ' + ((!obj.gliederung) ? 'null' : '"' + obj.gliederung.valueOf() + '"') + ',';
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<SchulformSchulgliederung>) : string {
		let result = '{';
		if (typeof obj.schulform !== "undefined") {
			result += '"schulform" : ' + '"' + obj.schulform.valueOf() + '"' + ',';
		}
		if (typeof obj.gliederung !== "undefined") {
			result += '"gliederung" : ' + ((!obj.gliederung) ? 'null' : '"' + obj.gliederung.valueOf() + '"') + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

}

export function cast_de_nrw_schule_svws_core_data_schule_SchulformSchulgliederung(obj : unknown) : SchulformSchulgliederung {
	return obj as SchulformSchulgliederung;
}