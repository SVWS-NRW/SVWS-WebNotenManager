import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaLong, cast_java_lang_Long } from '../../../java/lang/JavaLong';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';
import { Vector, cast_java_util_Vector } from '../../../java/util/Vector';

export class KlassenDaten extends JavaObject {

	public id : number = 0;

	public kuerzel : String | null = null;

	public idJahrgang : Number | null = null;

	public parallelitaet : String | null = null;

	public sortierung : number = 0;

	public istSichtbar : boolean = false;

	public klassenLeitungen : Vector<Number | null> | null = new Vector();


	public constructor() {
		super();
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.klassen.KlassenDaten'].includes(name);
	}

	public static transpilerFromJSON(json : string): KlassenDaten {
		const obj = JSON.parse(json);
		const result = new KlassenDaten();
		if (typeof obj.id === "undefined")
			 throw new Error('invalid json format, missing attribute id');
		result.id = obj.id;
		result.kuerzel = typeof obj.kuerzel === "undefined" ? null : obj.kuerzel;
		result.idJahrgang = typeof obj.idJahrgang === "undefined" ? null : obj.idJahrgang;
		result.parallelitaet = typeof obj.parallelitaet === "undefined" ? null : obj.parallelitaet;
		if (typeof obj.sortierung === "undefined")
			 throw new Error('invalid json format, missing attribute sortierung');
		result.sortierung = obj.sortierung;
		if (typeof obj.istSichtbar === "undefined")
			 throw new Error('invalid json format, missing attribute istSichtbar');
		result.istSichtbar = obj.istSichtbar;
		if (!!obj.klassenLeitungen) {
			for (let elem of obj.klassenLeitungen) {
				result.klassenLeitungen?.add(elem);
			}
		}
		return result;
	}

	public static transpilerToJSON(obj : KlassenDaten) : string {
		let result = '{';
		result += '"id" : ' + obj.id + ',';
		result += '"kuerzel" : ' + ((!obj.kuerzel) ? 'null' : '"' + obj.kuerzel.valueOf() + '"') + ',';
		result += '"idJahrgang" : ' + ((!obj.idJahrgang) ? 'null' : obj.idJahrgang.valueOf()) + ',';
		result += '"parallelitaet" : ' + ((!obj.parallelitaet) ? 'null' : '"' + obj.parallelitaet.valueOf() + '"') + ',';
		result += '"sortierung" : ' + obj.sortierung + ',';
		result += '"istSichtbar" : ' + obj.istSichtbar + ',';
		if (!obj.klassenLeitungen) {
			result += '"klassenLeitungen" : []';
		} else {
			result += '"klassenLeitungen" : [ ';
			for (let i : number = 0; i < obj.klassenLeitungen.size(); i++) {
				let elem = obj.klassenLeitungen.get(i);
				result += (elem == null) ? null : elem;
				if (i < obj.klassenLeitungen.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<KlassenDaten>) : string {
		let result = '{';
		if (typeof obj.id !== "undefined") {
			result += '"id" : ' + obj.id + ',';
		}
		if (typeof obj.kuerzel !== "undefined") {
			result += '"kuerzel" : ' + ((!obj.kuerzel) ? 'null' : '"' + obj.kuerzel.valueOf() + '"') + ',';
		}
		if (typeof obj.idJahrgang !== "undefined") {
			result += '"idJahrgang" : ' + ((!obj.idJahrgang) ? 'null' : obj.idJahrgang.valueOf()) + ',';
		}
		if (typeof obj.parallelitaet !== "undefined") {
			result += '"parallelitaet" : ' + ((!obj.parallelitaet) ? 'null' : '"' + obj.parallelitaet.valueOf() + '"') + ',';
		}
		if (typeof obj.sortierung !== "undefined") {
			result += '"sortierung" : ' + obj.sortierung + ',';
		}
		if (typeof obj.istSichtbar !== "undefined") {
			result += '"istSichtbar" : ' + obj.istSichtbar + ',';
		}
		if (typeof obj.klassenLeitungen !== "undefined") {
			if (!obj.klassenLeitungen) {
				result += '"klassenLeitungen" : []';
			} else {
				result += '"klassenLeitungen" : [ ';
				for (let i : number = 0; i < obj.klassenLeitungen.size(); i++) {
					let elem = obj.klassenLeitungen.get(i);
					result += (elem == null) ? null : elem;
					if (i < obj.klassenLeitungen.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

}

export function cast_de_nrw_schule_svws_core_data_klassen_KlassenDaten(obj : unknown) : KlassenDaten {
	return obj as KlassenDaten;
}
