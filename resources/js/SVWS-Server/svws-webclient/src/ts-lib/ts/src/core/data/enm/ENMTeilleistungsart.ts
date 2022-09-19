import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaInteger, cast_java_lang_Integer } from '../../../java/lang/JavaInteger';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';
import { JavaDouble, cast_java_lang_Double } from '../../../java/lang/JavaDouble';

export class ENMTeilleistungsart extends JavaObject {

	public id : number = 0;

	public bezeichnung : String | null = null;

	public sortierung : Number | null = null;

	public gewichtung : Number | null = null;


	public constructor() {
		super();
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.enm.ENMTeilleistungsart'].includes(name);
	}

	public static transpilerFromJSON(json : string): ENMTeilleistungsart {
		const obj = JSON.parse(json);
		const result = new ENMTeilleistungsart();
		if (typeof obj.id === "undefined")
			 throw new Error('invalid json format, missing attribute id');
		result.id = obj.id;
		result.bezeichnung = typeof obj.bezeichnung === "undefined" ? null : obj.bezeichnung;
		result.sortierung = typeof obj.sortierung === "undefined" ? null : obj.sortierung;
		result.gewichtung = typeof obj.gewichtung === "undefined" ? null : obj.gewichtung;
		return result;
	}

	public static transpilerToJSON(obj : ENMTeilleistungsart) : string {
		let result = '{';
		result += '"id" : ' + obj.id + ',';
		result += '"bezeichnung" : ' + ((!obj.bezeichnung) ? 'null' : '"' + obj.bezeichnung.valueOf() + '"') + ',';
		result += '"sortierung" : ' + ((!obj.sortierung) ? 'null' : obj.sortierung.valueOf()) + ',';
		result += '"gewichtung" : ' + ((!obj.gewichtung) ? 'null' : obj.gewichtung.valueOf()) + ',';
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<ENMTeilleistungsart>) : string {
		let result = '{';
		if (typeof obj.id !== "undefined") {
			result += '"id" : ' + obj.id + ',';
		}
		if (typeof obj.bezeichnung !== "undefined") {
			result += '"bezeichnung" : ' + ((!obj.bezeichnung) ? 'null' : '"' + obj.bezeichnung.valueOf() + '"') + ',';
		}
		if (typeof obj.sortierung !== "undefined") {
			result += '"sortierung" : ' + ((!obj.sortierung) ? 'null' : obj.sortierung.valueOf()) + ',';
		}
		if (typeof obj.gewichtung !== "undefined") {
			result += '"gewichtung" : ' + ((!obj.gewichtung) ? 'null' : obj.gewichtung.valueOf()) + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

}

export function cast_de_nrw_schule_svws_core_data_enm_ENMTeilleistungsart(obj : unknown) : ENMTeilleistungsart {
	return obj as ENMTeilleistungsart;
}
