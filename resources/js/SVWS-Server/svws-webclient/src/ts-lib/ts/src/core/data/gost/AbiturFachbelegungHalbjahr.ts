import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaLong, cast_java_lang_Long } from '../../../java/lang/JavaLong';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';
import { JavaBoolean, cast_java_lang_Boolean } from '../../../java/lang/JavaBoolean';

export class AbiturFachbelegungHalbjahr extends JavaObject {

	public halbjahrKuerzel : String = "";

	public kursartKuerzel : String = "";

	public schriftlich : Boolean | null = null;

	public biliSprache : String | null = null;

	public lehrer : Number | null = null;

	public wochenstunden : number = 0;

	public fehlstundenGesamt : number = 0;

	public fehlstundenUnentschuldigt : number = 0;

	public notenkuerzel : String | null = null;

	public block1gewertet : Boolean | null = null;

	public block1kursAufZeugnis : Boolean | null = null;


	public constructor() {
		super();
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.gost.AbiturFachbelegungHalbjahr'].includes(name);
	}

	public static transpilerFromJSON(json : string): AbiturFachbelegungHalbjahr {
		const obj = JSON.parse(json);
		const result = new AbiturFachbelegungHalbjahr();
		if (typeof obj.halbjahrKuerzel === "undefined")
			 throw new Error('invalid json format, missing attribute halbjahrKuerzel');
		result.halbjahrKuerzel = obj.halbjahrKuerzel;
		if (typeof obj.kursartKuerzel === "undefined")
			 throw new Error('invalid json format, missing attribute kursartKuerzel');
		result.kursartKuerzel = obj.kursartKuerzel;
		result.schriftlich = typeof obj.schriftlich === "undefined" ? null : obj.schriftlich;
		result.biliSprache = typeof obj.biliSprache === "undefined" ? null : obj.biliSprache;
		result.lehrer = typeof obj.lehrer === "undefined" ? null : obj.lehrer;
		if (typeof obj.wochenstunden === "undefined")
			 throw new Error('invalid json format, missing attribute wochenstunden');
		result.wochenstunden = obj.wochenstunden;
		if (typeof obj.fehlstundenGesamt === "undefined")
			 throw new Error('invalid json format, missing attribute fehlstundenGesamt');
		result.fehlstundenGesamt = obj.fehlstundenGesamt;
		if (typeof obj.fehlstundenUnentschuldigt === "undefined")
			 throw new Error('invalid json format, missing attribute fehlstundenUnentschuldigt');
		result.fehlstundenUnentschuldigt = obj.fehlstundenUnentschuldigt;
		result.notenkuerzel = typeof obj.notenkuerzel === "undefined" ? null : obj.notenkuerzel;
		result.block1gewertet = typeof obj.block1gewertet === "undefined" ? null : obj.block1gewertet;
		result.block1kursAufZeugnis = typeof obj.block1kursAufZeugnis === "undefined" ? null : obj.block1kursAufZeugnis;
		return result;
	}

	public static transpilerToJSON(obj : AbiturFachbelegungHalbjahr) : string {
		let result = '{';
		result += '"halbjahrKuerzel" : ' + '"' + obj.halbjahrKuerzel.valueOf() + '"' + ',';
		result += '"kursartKuerzel" : ' + '"' + obj.kursartKuerzel.valueOf() + '"' + ',';
		result += '"schriftlich" : ' + ((!obj.schriftlich) ? 'null' : obj.schriftlich.valueOf()) + ',';
		result += '"biliSprache" : ' + ((!obj.biliSprache) ? 'null' : '"' + obj.biliSprache.valueOf() + '"') + ',';
		result += '"lehrer" : ' + ((!obj.lehrer) ? 'null' : obj.lehrer.valueOf()) + ',';
		result += '"wochenstunden" : ' + obj.wochenstunden + ',';
		result += '"fehlstundenGesamt" : ' + obj.fehlstundenGesamt + ',';
		result += '"fehlstundenUnentschuldigt" : ' + obj.fehlstundenUnentschuldigt + ',';
		result += '"notenkuerzel" : ' + ((!obj.notenkuerzel) ? 'null' : '"' + obj.notenkuerzel.valueOf() + '"') + ',';
		result += '"block1gewertet" : ' + ((!obj.block1gewertet) ? 'null' : obj.block1gewertet.valueOf()) + ',';
		result += '"block1kursAufZeugnis" : ' + ((!obj.block1kursAufZeugnis) ? 'null' : obj.block1kursAufZeugnis.valueOf()) + ',';
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<AbiturFachbelegungHalbjahr>) : string {
		let result = '{';
		if (typeof obj.halbjahrKuerzel !== "undefined") {
			result += '"halbjahrKuerzel" : ' + '"' + obj.halbjahrKuerzel.valueOf() + '"' + ',';
		}
		if (typeof obj.kursartKuerzel !== "undefined") {
			result += '"kursartKuerzel" : ' + '"' + obj.kursartKuerzel.valueOf() + '"' + ',';
		}
		if (typeof obj.schriftlich !== "undefined") {
			result += '"schriftlich" : ' + ((!obj.schriftlich) ? 'null' : obj.schriftlich.valueOf()) + ',';
		}
		if (typeof obj.biliSprache !== "undefined") {
			result += '"biliSprache" : ' + ((!obj.biliSprache) ? 'null' : '"' + obj.biliSprache.valueOf() + '"') + ',';
		}
		if (typeof obj.lehrer !== "undefined") {
			result += '"lehrer" : ' + ((!obj.lehrer) ? 'null' : obj.lehrer.valueOf()) + ',';
		}
		if (typeof obj.wochenstunden !== "undefined") {
			result += '"wochenstunden" : ' + obj.wochenstunden + ',';
		}
		if (typeof obj.fehlstundenGesamt !== "undefined") {
			result += '"fehlstundenGesamt" : ' + obj.fehlstundenGesamt + ',';
		}
		if (typeof obj.fehlstundenUnentschuldigt !== "undefined") {
			result += '"fehlstundenUnentschuldigt" : ' + obj.fehlstundenUnentschuldigt + ',';
		}
		if (typeof obj.notenkuerzel !== "undefined") {
			result += '"notenkuerzel" : ' + ((!obj.notenkuerzel) ? 'null' : '"' + obj.notenkuerzel.valueOf() + '"') + ',';
		}
		if (typeof obj.block1gewertet !== "undefined") {
			result += '"block1gewertet" : ' + ((!obj.block1gewertet) ? 'null' : obj.block1gewertet.valueOf()) + ',';
		}
		if (typeof obj.block1kursAufZeugnis !== "undefined") {
			result += '"block1kursAufZeugnis" : ' + ((!obj.block1kursAufZeugnis) ? 'null' : obj.block1kursAufZeugnis.valueOf()) + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

}

export function cast_de_nrw_schule_svws_core_data_gost_AbiturFachbelegungHalbjahr(obj : unknown) : AbiturFachbelegungHalbjahr {
	return obj as AbiturFachbelegungHalbjahr;
}
