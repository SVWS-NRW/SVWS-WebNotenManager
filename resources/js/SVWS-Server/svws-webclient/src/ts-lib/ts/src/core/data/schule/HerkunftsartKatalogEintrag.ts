import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaInteger, cast_java_lang_Integer } from '../../../java/lang/JavaInteger';
import { List, cast_java_util_List } from '../../../java/util/List';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';
import { HerkunftsartKatalogEintragBezeichnung, cast_de_nrw_schule_svws_core_data_schule_HerkunftsartKatalogEintragBezeichnung } from '../../../core/data/schule/HerkunftsartKatalogEintragBezeichnung';
import { Vector, cast_java_util_Vector } from '../../../java/util/Vector';

export class HerkunftsartKatalogEintrag extends JavaObject {

	public id : number = 0;

	public kuerzel : String = "";

	public bezeichnungen : List<HerkunftsartKatalogEintragBezeichnung> = new Vector();

	public gueltigVon : Number | null = null;

	public gueltigBis : Number | null = null;


	/**
	 * Erstellt einen Katalog-Eintrag mit Standardwerten
	 */
	public constructor();

	/**
	 * Erstellt einen Katalog-Eintrag mit den angegebenen Werten
	 *
	 * @param id              die ID des Katalog-Eintrags
	 * @param kuerzel         das 2-stellige Kürzel der Herkunftsart
	 * @param bezeichnungen   die Bezeichnungen bei den jeweils zulässigen Schulformen
	 * @param gueltigVon      das Schuljahr, wann der Eintrag eingeführt wurde oder null, falls es nicht bekannt ist und "schon immer gültig war"
	 * @param gueltigBis      das Schuljahr, bis zu welchem der Eintrag gültig ist
	 */
	public constructor(id : number, kuerzel : String, bezeichnungen : List<HerkunftsartKatalogEintragBezeichnung>, gueltigVon : Number | null, gueltigBis : Number | null);

	/**
	 * Implementation for method overloads of 'constructor'
	 */
	public constructor(__param0? : number, __param1? : String, __param2? : List<HerkunftsartKatalogEintragBezeichnung>, __param3? : Number | null, __param4? : Number | null) {
		super();
		if ((typeof __param0 === "undefined") && (typeof __param1 === "undefined") && (typeof __param2 === "undefined") && (typeof __param3 === "undefined") && (typeof __param4 === "undefined")) {
			} else if (((typeof __param0 !== "undefined") && typeof __param0 === "number") && ((typeof __param1 !== "undefined") && ((__param1 instanceof String) || (typeof __param1 === "string"))) && ((typeof __param2 !== "undefined") && ((__param2 instanceof JavaObject) && (__param2.isTranspiledInstanceOf('java.util.List'))) || (__param2 === null)) && ((typeof __param3 !== "undefined") && ((__param3 instanceof Number) || (typeof __param3 === "number")) || (__param3 === null)) && ((typeof __param4 !== "undefined") && ((__param4 instanceof Number) || (typeof __param4 === "number")) || (__param4 === null))) {
			let id : number = __param0 as number;
			let kuerzel : String = __param1;
			let bezeichnungen : List<HerkunftsartKatalogEintragBezeichnung> = cast_java_util_List(__param2);
			let gueltigVon : Number | null = cast_java_lang_Integer(__param3);
			let gueltigBis : Number | null = cast_java_lang_Integer(__param4);
			this.id = id;
			this.kuerzel = kuerzel;
			this.bezeichnungen = bezeichnungen;
			this.gueltigVon = gueltigVon;
			this.gueltigBis = gueltigBis;
		} else throw new Error('invalid method overload');
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.schule.HerkunftsartKatalogEintrag'].includes(name);
	}

	public static transpilerFromJSON(json : string): HerkunftsartKatalogEintrag {
		const obj = JSON.parse(json);
		const result = new HerkunftsartKatalogEintrag();
		if (typeof obj.id === "undefined")
			 throw new Error('invalid json format, missing attribute id');
		result.id = obj.id;
		if (typeof obj.kuerzel === "undefined")
			 throw new Error('invalid json format, missing attribute kuerzel');
		result.kuerzel = obj.kuerzel;
		if (!!obj.bezeichnungen) {
			for (let elem of obj.bezeichnungen) {
				result.bezeichnungen?.add(HerkunftsartKatalogEintragBezeichnung.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		result.gueltigVon = typeof obj.gueltigVon === "undefined" ? null : obj.gueltigVon;
		result.gueltigBis = typeof obj.gueltigBis === "undefined" ? null : obj.gueltigBis;
		return result;
	}

	public static transpilerToJSON(obj : HerkunftsartKatalogEintrag) : string {
		let result = '{';
		result += '"id" : ' + obj.id + ',';
		result += '"kuerzel" : ' + '"' + obj.kuerzel.valueOf() + '"' + ',';
		if (!obj.bezeichnungen) {
			result += '"bezeichnungen" : []';
		} else {
			result += '"bezeichnungen" : [ ';
			for (let i : number = 0; i < obj.bezeichnungen.size(); i++) {
				let elem = obj.bezeichnungen.get(i);
				result += HerkunftsartKatalogEintragBezeichnung.transpilerToJSON(elem);
				if (i < obj.bezeichnungen.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		result += '"gueltigVon" : ' + ((!obj.gueltigVon) ? 'null' : obj.gueltigVon.valueOf()) + ',';
		result += '"gueltigBis" : ' + ((!obj.gueltigBis) ? 'null' : obj.gueltigBis.valueOf()) + ',';
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<HerkunftsartKatalogEintrag>) : string {
		let result = '{';
		if (typeof obj.id !== "undefined") {
			result += '"id" : ' + obj.id + ',';
		}
		if (typeof obj.kuerzel !== "undefined") {
			result += '"kuerzel" : ' + '"' + obj.kuerzel.valueOf() + '"' + ',';
		}
		if (typeof obj.bezeichnungen !== "undefined") {
			if (!obj.bezeichnungen) {
				result += '"bezeichnungen" : []';
			} else {
				result += '"bezeichnungen" : [ ';
				for (let i : number = 0; i < obj.bezeichnungen.size(); i++) {
					let elem = obj.bezeichnungen.get(i);
					result += HerkunftsartKatalogEintragBezeichnung.transpilerToJSON(elem);
					if (i < obj.bezeichnungen.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.gueltigVon !== "undefined") {
			result += '"gueltigVon" : ' + ((!obj.gueltigVon) ? 'null' : obj.gueltigVon.valueOf()) + ',';
		}
		if (typeof obj.gueltigBis !== "undefined") {
			result += '"gueltigBis" : ' + ((!obj.gueltigBis) ? 'null' : obj.gueltigBis.valueOf()) + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

}

export function cast_de_nrw_schule_svws_core_data_schule_HerkunftsartKatalogEintrag(obj : unknown) : HerkunftsartKatalogEintrag {
	return obj as HerkunftsartKatalogEintrag;
}
