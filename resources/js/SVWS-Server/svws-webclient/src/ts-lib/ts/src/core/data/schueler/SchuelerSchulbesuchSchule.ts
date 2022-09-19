import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaLong, cast_java_lang_Long } from '../../../java/lang/JavaLong';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';

export class SchuelerSchulbesuchSchule extends JavaObject {

	public schulnummer : String = "";

	public schulgliederung : String | null = null;

	public entlassgrundID : Number | null = null;

	public abschlussartID : String | null = null;

	public organisationsFormID : String | null = null;

	public datumVon : String | null = null;

	public datumBis : String | null = null;

	public jahrgangVon : String | null = null;

	public jahrgangBis : String | null = null;


	public constructor() {
		super();
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.schueler.SchuelerSchulbesuchSchule'].includes(name);
	}

	public static transpilerFromJSON(json : string): SchuelerSchulbesuchSchule {
		const obj = JSON.parse(json);
		const result = new SchuelerSchulbesuchSchule();
		if (typeof obj.schulnummer === "undefined")
			 throw new Error('invalid json format, missing attribute schulnummer');
		result.schulnummer = obj.schulnummer;
		result.schulgliederung = typeof obj.schulgliederung === "undefined" ? null : obj.schulgliederung;
		result.entlassgrundID = typeof obj.entlassgrundID === "undefined" ? null : obj.entlassgrundID;
		result.abschlussartID = typeof obj.abschlussartID === "undefined" ? null : obj.abschlussartID;
		result.organisationsFormID = typeof obj.organisationsFormID === "undefined" ? null : obj.organisationsFormID;
		result.datumVon = typeof obj.datumVon === "undefined" ? null : obj.datumVon;
		result.datumBis = typeof obj.datumBis === "undefined" ? null : obj.datumBis;
		result.jahrgangVon = typeof obj.jahrgangVon === "undefined" ? null : obj.jahrgangVon;
		result.jahrgangBis = typeof obj.jahrgangBis === "undefined" ? null : obj.jahrgangBis;
		return result;
	}

	public static transpilerToJSON(obj : SchuelerSchulbesuchSchule) : string {
		let result = '{';
		result += '"schulnummer" : ' + '"' + obj.schulnummer.valueOf() + '"' + ',';
		result += '"schulgliederung" : ' + ((!obj.schulgliederung) ? 'null' : '"' + obj.schulgliederung.valueOf() + '"') + ',';
		result += '"entlassgrundID" : ' + ((!obj.entlassgrundID) ? 'null' : obj.entlassgrundID.valueOf()) + ',';
		result += '"abschlussartID" : ' + ((!obj.abschlussartID) ? 'null' : '"' + obj.abschlussartID.valueOf() + '"') + ',';
		result += '"organisationsFormID" : ' + ((!obj.organisationsFormID) ? 'null' : '"' + obj.organisationsFormID.valueOf() + '"') + ',';
		result += '"datumVon" : ' + ((!obj.datumVon) ? 'null' : '"' + obj.datumVon.valueOf() + '"') + ',';
		result += '"datumBis" : ' + ((!obj.datumBis) ? 'null' : '"' + obj.datumBis.valueOf() + '"') + ',';
		result += '"jahrgangVon" : ' + ((!obj.jahrgangVon) ? 'null' : '"' + obj.jahrgangVon.valueOf() + '"') + ',';
		result += '"jahrgangBis" : ' + ((!obj.jahrgangBis) ? 'null' : '"' + obj.jahrgangBis.valueOf() + '"') + ',';
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<SchuelerSchulbesuchSchule>) : string {
		let result = '{';
		if (typeof obj.schulnummer !== "undefined") {
			result += '"schulnummer" : ' + '"' + obj.schulnummer.valueOf() + '"' + ',';
		}
		if (typeof obj.schulgliederung !== "undefined") {
			result += '"schulgliederung" : ' + ((!obj.schulgliederung) ? 'null' : '"' + obj.schulgliederung.valueOf() + '"') + ',';
		}
		if (typeof obj.entlassgrundID !== "undefined") {
			result += '"entlassgrundID" : ' + ((!obj.entlassgrundID) ? 'null' : obj.entlassgrundID.valueOf()) + ',';
		}
		if (typeof obj.abschlussartID !== "undefined") {
			result += '"abschlussartID" : ' + ((!obj.abschlussartID) ? 'null' : '"' + obj.abschlussartID.valueOf() + '"') + ',';
		}
		if (typeof obj.organisationsFormID !== "undefined") {
			result += '"organisationsFormID" : ' + ((!obj.organisationsFormID) ? 'null' : '"' + obj.organisationsFormID.valueOf() + '"') + ',';
		}
		if (typeof obj.datumVon !== "undefined") {
			result += '"datumVon" : ' + ((!obj.datumVon) ? 'null' : '"' + obj.datumVon.valueOf() + '"') + ',';
		}
		if (typeof obj.datumBis !== "undefined") {
			result += '"datumBis" : ' + ((!obj.datumBis) ? 'null' : '"' + obj.datumBis.valueOf() + '"') + ',';
		}
		if (typeof obj.jahrgangVon !== "undefined") {
			result += '"jahrgangVon" : ' + ((!obj.jahrgangVon) ? 'null' : '"' + obj.jahrgangVon.valueOf() + '"') + ',';
		}
		if (typeof obj.jahrgangBis !== "undefined") {
			result += '"jahrgangBis" : ' + ((!obj.jahrgangBis) ? 'null' : '"' + obj.jahrgangBis.valueOf() + '"') + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

}

export function cast_de_nrw_schule_svws_core_data_schueler_SchuelerSchulbesuchSchule(obj : unknown) : SchuelerSchulbesuchSchule {
	return obj as SchuelerSchulbesuchSchule;
}
