import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';

export class GostFachwahl extends JavaObject {

	public id : number = -1;

	public fachID : number = -1;

	public halbjahrID : number = -1;

	public schuelerID : number = -1;

	public schuelerNachname : String = "";

	public schuelerVorname : String = "";

	public kursartID : number = -1;

	public istSchriftlich : boolean = false;


	public constructor() {
		super();
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.gost.GostFachwahl'].includes(name);
	}

	public static transpilerFromJSON(json : string): GostFachwahl {
		const obj = JSON.parse(json);
		const result = new GostFachwahl();
		if (typeof obj.id === "undefined")
			 throw new Error('invalid json format, missing attribute id');
		result.id = obj.id;
		if (typeof obj.fachID === "undefined")
			 throw new Error('invalid json format, missing attribute fachID');
		result.fachID = obj.fachID;
		if (typeof obj.halbjahrID === "undefined")
			 throw new Error('invalid json format, missing attribute halbjahrID');
		result.halbjahrID = obj.halbjahrID;
		if (typeof obj.schuelerID === "undefined")
			 throw new Error('invalid json format, missing attribute schuelerID');
		result.schuelerID = obj.schuelerID;
		if (typeof obj.schuelerNachname === "undefined")
			 throw new Error('invalid json format, missing attribute schuelerNachname');
		result.schuelerNachname = obj.schuelerNachname;
		if (typeof obj.schuelerVorname === "undefined")
			 throw new Error('invalid json format, missing attribute schuelerVorname');
		result.schuelerVorname = obj.schuelerVorname;
		if (typeof obj.kursartID === "undefined")
			 throw new Error('invalid json format, missing attribute kursartID');
		result.kursartID = obj.kursartID;
		if (typeof obj.istSchriftlich === "undefined")
			 throw new Error('invalid json format, missing attribute istSchriftlich');
		result.istSchriftlich = obj.istSchriftlich;
		return result;
	}

	public static transpilerToJSON(obj : GostFachwahl) : string {
		let result = '{';
		result += '"id" : ' + obj.id + ',';
		result += '"fachID" : ' + obj.fachID + ',';
		result += '"halbjahrID" : ' + obj.halbjahrID + ',';
		result += '"schuelerID" : ' + obj.schuelerID + ',';
		result += '"schuelerNachname" : ' + '"' + obj.schuelerNachname.valueOf() + '"' + ',';
		result += '"schuelerVorname" : ' + '"' + obj.schuelerVorname.valueOf() + '"' + ',';
		result += '"kursartID" : ' + obj.kursartID + ',';
		result += '"istSchriftlich" : ' + obj.istSchriftlich + ',';
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<GostFachwahl>) : string {
		let result = '{';
		if (typeof obj.id !== "undefined") {
			result += '"id" : ' + obj.id + ',';
		}
		if (typeof obj.fachID !== "undefined") {
			result += '"fachID" : ' + obj.fachID + ',';
		}
		if (typeof obj.halbjahrID !== "undefined") {
			result += '"halbjahrID" : ' + obj.halbjahrID + ',';
		}
		if (typeof obj.schuelerID !== "undefined") {
			result += '"schuelerID" : ' + obj.schuelerID + ',';
		}
		if (typeof obj.schuelerNachname !== "undefined") {
			result += '"schuelerNachname" : ' + '"' + obj.schuelerNachname.valueOf() + '"' + ',';
		}
		if (typeof obj.schuelerVorname !== "undefined") {
			result += '"schuelerVorname" : ' + '"' + obj.schuelerVorname.valueOf() + '"' + ',';
		}
		if (typeof obj.kursartID !== "undefined") {
			result += '"kursartID" : ' + obj.kursartID + ',';
		}
		if (typeof obj.istSchriftlich !== "undefined") {
			result += '"istSchriftlich" : ' + obj.istSchriftlich + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

}

export function cast_de_nrw_schule_svws_core_data_gost_GostFachwahl(obj : unknown) : GostFachwahl {
	return obj as GostFachwahl;
}
