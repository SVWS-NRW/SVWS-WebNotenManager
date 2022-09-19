import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaInteger, cast_java_lang_Integer } from '../../../java/lang/JavaInteger';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';

export class GostSchuelerFachwahl extends JavaObject {

	public EF1 : String | null = null;

	public EF2 : String | null = null;

	public Q11 : String | null = null;

	public Q12 : String | null = null;

	public Q21 : String | null = null;

	public Q22 : String | null = null;

	public abiturFach : Number | null = null;


	public constructor() {
		super();
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.gost.GostSchuelerFachwahl'].includes(name);
	}

	public static transpilerFromJSON(json : string): GostSchuelerFachwahl {
		const obj = JSON.parse(json);
		const result = new GostSchuelerFachwahl();
		result.EF1 = typeof obj.EF1 === "undefined" ? null : obj.EF1;
		result.EF2 = typeof obj.EF2 === "undefined" ? null : obj.EF2;
		result.Q11 = typeof obj.Q11 === "undefined" ? null : obj.Q11;
		result.Q12 = typeof obj.Q12 === "undefined" ? null : obj.Q12;
		result.Q21 = typeof obj.Q21 === "undefined" ? null : obj.Q21;
		result.Q22 = typeof obj.Q22 === "undefined" ? null : obj.Q22;
		result.abiturFach = typeof obj.abiturFach === "undefined" ? null : obj.abiturFach;
		return result;
	}

	public static transpilerToJSON(obj : GostSchuelerFachwahl) : string {
		let result = '{';
		result += '"EF1" : ' + ((!obj.EF1) ? 'null' : '"' + obj.EF1.valueOf() + '"') + ',';
		result += '"EF2" : ' + ((!obj.EF2) ? 'null' : '"' + obj.EF2.valueOf() + '"') + ',';
		result += '"Q11" : ' + ((!obj.Q11) ? 'null' : '"' + obj.Q11.valueOf() + '"') + ',';
		result += '"Q12" : ' + ((!obj.Q12) ? 'null' : '"' + obj.Q12.valueOf() + '"') + ',';
		result += '"Q21" : ' + ((!obj.Q21) ? 'null' : '"' + obj.Q21.valueOf() + '"') + ',';
		result += '"Q22" : ' + ((!obj.Q22) ? 'null' : '"' + obj.Q22.valueOf() + '"') + ',';
		result += '"abiturFach" : ' + ((!obj.abiturFach) ? 'null' : obj.abiturFach.valueOf()) + ',';
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<GostSchuelerFachwahl>) : string {
		let result = '{';
		if (typeof obj.EF1 !== "undefined") {
			result += '"EF1" : ' + ((!obj.EF1) ? 'null' : '"' + obj.EF1.valueOf() + '"') + ',';
		}
		if (typeof obj.EF2 !== "undefined") {
			result += '"EF2" : ' + ((!obj.EF2) ? 'null' : '"' + obj.EF2.valueOf() + '"') + ',';
		}
		if (typeof obj.Q11 !== "undefined") {
			result += '"Q11" : ' + ((!obj.Q11) ? 'null' : '"' + obj.Q11.valueOf() + '"') + ',';
		}
		if (typeof obj.Q12 !== "undefined") {
			result += '"Q12" : ' + ((!obj.Q12) ? 'null' : '"' + obj.Q12.valueOf() + '"') + ',';
		}
		if (typeof obj.Q21 !== "undefined") {
			result += '"Q21" : ' + ((!obj.Q21) ? 'null' : '"' + obj.Q21.valueOf() + '"') + ',';
		}
		if (typeof obj.Q22 !== "undefined") {
			result += '"Q22" : ' + ((!obj.Q22) ? 'null' : '"' + obj.Q22.valueOf() + '"') + ',';
		}
		if (typeof obj.abiturFach !== "undefined") {
			result += '"abiturFach" : ' + ((!obj.abiturFach) ? 'null' : obj.abiturFach.valueOf()) + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

}

export function cast_de_nrw_schule_svws_core_data_gost_GostSchuelerFachwahl(obj : unknown) : GostSchuelerFachwahl {
	return obj as GostSchuelerFachwahl;
}
