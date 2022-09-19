import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaInteger, cast_java_lang_Integer } from '../../../java/lang/JavaInteger';
import { Schulform, cast_de_nrw_schule_svws_core_types_statkue_Schulform } from '../../../core/types/statkue/Schulform';
import { List, cast_java_util_List } from '../../../java/util/List';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';
import { Vector, cast_java_util_Vector } from '../../../java/util/Vector';

export class AllgemeineMerkmaleKatalogEintrag extends JavaObject {

	public id : number = -1;

	public kuerzel : String = "";

	public bezeichnung : String = "";

	public beiSchule : boolean = false;

	public beiSchueler : boolean = false;

	public kuerzelASD : String | null = "";

	public schulformen : List<String> = new Vector();

	public gueltigVon : Number | null = null;

	public gueltigBis : Number | null = null;


	/**
	 * Erstellt einen Eintrag mit Standardwerten
	 */
	public constructor();

	/**
	 * Erstellt einen Eintrag mit den angegebenen Werten
	 * 
	 * @param id              die ID
	 * @param kuerzel         das Kürzel
	 * @param bezeichnung     die Bezeichnung des Merkmals
	 * @param beiSchule       gibt an, das das Merkmal bei der Schule gesetzt werden kann
	 * @param beiSchueler     gibt an, das das Merkmal bei einem Schüler gesetzt werden kann
	 * @param kuerzelASD      ggf. ein Kürzel, welches im Rahmen der amtlichen Schulstatistik verwendet wird
	 * @param schulformen     die Schulformen, bei welchen das allgemeine Merkmal vorkommen kann
	 * @param gueltigVon      das Schuljahr, wann der Eintrag eingeführt wurde oder null, falls es nicht bekannt ist und "schon immer gültig war"
	 * @param gueltigBis      das Schuljahr, bis zu welchem der Eintrag gültig ist
	 */
	public constructor(id : number, kuerzel : String, bezeichnung : String, beiSchule : boolean, beiSchueler : boolean, kuerzelASD : String | null, schulformen : List<Schulform>, gueltigVon : Number | null, gueltigBis : Number | null);

	/**
	 * Implementation for method overloads of 'constructor'
	 */
	public constructor(__param0? : number, __param1? : String, __param2? : String, __param3? : boolean, __param4? : boolean, __param5? : String | null, __param6? : List<Schulform>, __param7? : Number | null, __param8? : Number | null) {
		super();
		if ((typeof __param0 === "undefined") && (typeof __param1 === "undefined") && (typeof __param2 === "undefined") && (typeof __param3 === "undefined") && (typeof __param4 === "undefined") && (typeof __param5 === "undefined") && (typeof __param6 === "undefined") && (typeof __param7 === "undefined") && (typeof __param8 === "undefined")) {
			} else if (((typeof __param0 !== "undefined") && typeof __param0 === "number") && ((typeof __param1 !== "undefined") && ((__param1 instanceof String) || (typeof __param1 === "string"))) && ((typeof __param2 !== "undefined") && ((__param2 instanceof String) || (typeof __param2 === "string"))) && ((typeof __param3 !== "undefined") && typeof __param3 === "boolean") && ((typeof __param4 !== "undefined") && typeof __param4 === "boolean") && ((typeof __param5 !== "undefined") && ((__param5 instanceof String) || (typeof __param5 === "string")) || (__param5 === null)) && ((typeof __param6 !== "undefined") && ((__param6 instanceof JavaObject) && (__param6.isTranspiledInstanceOf('java.util.List'))) || (__param6 === null)) && ((typeof __param7 !== "undefined") && ((__param7 instanceof Number) || (typeof __param7 === "number")) || (__param7 === null)) && ((typeof __param8 !== "undefined") && ((__param8 instanceof Number) || (typeof __param8 === "number")) || (__param8 === null))) {
			let id : number = __param0 as number;
			let kuerzel : String = __param1;
			let bezeichnung : String = __param2;
			let beiSchule : boolean = __param3 as boolean;
			let beiSchueler : boolean = __param4 as boolean;
			let kuerzelASD : String | null = __param5;
			let schulformen : List<Schulform> = cast_java_util_List(__param6);
			let gueltigVon : Number | null = cast_java_lang_Integer(__param7);
			let gueltigBis : Number | null = cast_java_lang_Integer(__param8);
			this.id = id;
			this.kuerzel = kuerzel;
			this.bezeichnung = bezeichnung;
			this.beiSchule = beiSchule;
			this.beiSchueler = beiSchueler;
			this.kuerzelASD = kuerzelASD;
			for (let sf of schulformen) 
				this.schulformen.add(sf.daten.kuerzel);
			this.gueltigVon = gueltigVon;
			this.gueltigBis = gueltigBis;
		} else throw new Error('invalid method overload');
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.schule.AllgemeineMerkmaleKatalogEintrag'].includes(name);
	}

	public static transpilerFromJSON(json : string): AllgemeineMerkmaleKatalogEintrag {
		const obj = JSON.parse(json);
		const result = new AllgemeineMerkmaleKatalogEintrag();
		if (typeof obj.id === "undefined")
			 throw new Error('invalid json format, missing attribute id');
		result.id = obj.id;
		if (typeof obj.kuerzel === "undefined")
			 throw new Error('invalid json format, missing attribute kuerzel');
		result.kuerzel = obj.kuerzel;
		if (typeof obj.bezeichnung === "undefined")
			 throw new Error('invalid json format, missing attribute bezeichnung');
		result.bezeichnung = obj.bezeichnung;
		if (typeof obj.beiSchule === "undefined")
			 throw new Error('invalid json format, missing attribute beiSchule');
		result.beiSchule = obj.beiSchule;
		if (typeof obj.beiSchueler === "undefined")
			 throw new Error('invalid json format, missing attribute beiSchueler');
		result.beiSchueler = obj.beiSchueler;
		result.kuerzelASD = typeof obj.kuerzelASD === "undefined" ? null : obj.kuerzelASD;
		if (!!obj.schulformen) {
			for (let elem of obj.schulformen) {
				result.schulformen?.add(elem);
			}
		}
		result.gueltigVon = typeof obj.gueltigVon === "undefined" ? null : obj.gueltigVon;
		result.gueltigBis = typeof obj.gueltigBis === "undefined" ? null : obj.gueltigBis;
		return result;
	}

	public static transpilerToJSON(obj : AllgemeineMerkmaleKatalogEintrag) : string {
		let result = '{';
		result += '"id" : ' + obj.id + ',';
		result += '"kuerzel" : ' + '"' + obj.kuerzel.valueOf() + '"' + ',';
		result += '"bezeichnung" : ' + '"' + obj.bezeichnung.valueOf() + '"' + ',';
		result += '"beiSchule" : ' + obj.beiSchule + ',';
		result += '"beiSchueler" : ' + obj.beiSchueler + ',';
		result += '"kuerzelASD" : ' + ((!obj.kuerzelASD) ? 'null' : '"' + obj.kuerzelASD.valueOf() + '"') + ',';
		if (!obj.schulformen) {
			result += '"schulformen" : []';
		} else {
			result += '"schulformen" : [ ';
			for (let i : number = 0; i < obj.schulformen.size(); i++) {
				let elem = obj.schulformen.get(i);
				result += '"' + elem + '"';
				if (i < obj.schulformen.size() - 1)
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

	public static transpilerToJSONPatch(obj : Partial<AllgemeineMerkmaleKatalogEintrag>) : string {
		let result = '{';
		if (typeof obj.id !== "undefined") {
			result += '"id" : ' + obj.id + ',';
		}
		if (typeof obj.kuerzel !== "undefined") {
			result += '"kuerzel" : ' + '"' + obj.kuerzel.valueOf() + '"' + ',';
		}
		if (typeof obj.bezeichnung !== "undefined") {
			result += '"bezeichnung" : ' + '"' + obj.bezeichnung.valueOf() + '"' + ',';
		}
		if (typeof obj.beiSchule !== "undefined") {
			result += '"beiSchule" : ' + obj.beiSchule + ',';
		}
		if (typeof obj.beiSchueler !== "undefined") {
			result += '"beiSchueler" : ' + obj.beiSchueler + ',';
		}
		if (typeof obj.kuerzelASD !== "undefined") {
			result += '"kuerzelASD" : ' + ((!obj.kuerzelASD) ? 'null' : '"' + obj.kuerzelASD.valueOf() + '"') + ',';
		}
		if (typeof obj.schulformen !== "undefined") {
			if (!obj.schulformen) {
				result += '"schulformen" : []';
			} else {
				result += '"schulformen" : [ ';
				for (let i : number = 0; i < obj.schulformen.size(); i++) {
					let elem = obj.schulformen.get(i);
					result += '"' + elem + '"';
					if (i < obj.schulformen.size() - 1)
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

export function cast_de_nrw_schule_svws_core_data_schule_AllgemeineMerkmaleKatalogEintrag(obj : unknown) : AllgemeineMerkmaleKatalogEintrag {
	return obj as AllgemeineMerkmaleKatalogEintrag;
}
