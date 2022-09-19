import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { ENMBKFach, cast_de_nrw_schule_svws_core_data_enm_ENMBKFach } from '../../../core/data/enm/ENMBKFach';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';
import { Vector, cast_java_util_Vector } from '../../../java/util/Vector';

export class ENMBKAbschluss extends JavaObject {

	public hatZulassung : boolean = false;

	public hatBestanden : boolean = false;

	public hatZulassungErweiterteBeruflicheKenntnisse : boolean = false;

	public hatErworbenErweiterteBeruflicheKenntnisse : boolean = false;

	public notePraktischePruefung : String | null = null;

	public noteKolloqium : String | null = null;

	public hatZulassungBerufsabschlusspruefung : boolean = false;

	public hatBestandenBerufsabschlusspruefung : boolean = false;

	public themaAbschlussarbeit : String | null = null;

	public istVorhandenBerufsabschlusspruefung : boolean = false;

	public noteFachpraxis : String | null = null;

	public istFachpraktischerTeilAusreichend : boolean = false;

	public faecher : Vector<ENMBKFach> = new Vector();


	public constructor() {
		super();
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.enm.ENMBKAbschluss'].includes(name);
	}

	public static transpilerFromJSON(json : string): ENMBKAbschluss {
		const obj = JSON.parse(json);
		const result = new ENMBKAbschluss();
		if (typeof obj.hatZulassung === "undefined")
			 throw new Error('invalid json format, missing attribute hatZulassung');
		result.hatZulassung = obj.hatZulassung;
		if (typeof obj.hatBestanden === "undefined")
			 throw new Error('invalid json format, missing attribute hatBestanden');
		result.hatBestanden = obj.hatBestanden;
		if (typeof obj.hatZulassungErweiterteBeruflicheKenntnisse === "undefined")
			 throw new Error('invalid json format, missing attribute hatZulassungErweiterteBeruflicheKenntnisse');
		result.hatZulassungErweiterteBeruflicheKenntnisse = obj.hatZulassungErweiterteBeruflicheKenntnisse;
		if (typeof obj.hatErworbenErweiterteBeruflicheKenntnisse === "undefined")
			 throw new Error('invalid json format, missing attribute hatErworbenErweiterteBeruflicheKenntnisse');
		result.hatErworbenErweiterteBeruflicheKenntnisse = obj.hatErworbenErweiterteBeruflicheKenntnisse;
		result.notePraktischePruefung = typeof obj.notePraktischePruefung === "undefined" ? null : obj.notePraktischePruefung;
		result.noteKolloqium = typeof obj.noteKolloqium === "undefined" ? null : obj.noteKolloqium;
		if (typeof obj.hatZulassungBerufsabschlusspruefung === "undefined")
			 throw new Error('invalid json format, missing attribute hatZulassungBerufsabschlusspruefung');
		result.hatZulassungBerufsabschlusspruefung = obj.hatZulassungBerufsabschlusspruefung;
		if (typeof obj.hatBestandenBerufsabschlusspruefung === "undefined")
			 throw new Error('invalid json format, missing attribute hatBestandenBerufsabschlusspruefung');
		result.hatBestandenBerufsabschlusspruefung = obj.hatBestandenBerufsabschlusspruefung;
		result.themaAbschlussarbeit = typeof obj.themaAbschlussarbeit === "undefined" ? null : obj.themaAbschlussarbeit;
		if (typeof obj.istVorhandenBerufsabschlusspruefung === "undefined")
			 throw new Error('invalid json format, missing attribute istVorhandenBerufsabschlusspruefung');
		result.istVorhandenBerufsabschlusspruefung = obj.istVorhandenBerufsabschlusspruefung;
		result.noteFachpraxis = typeof obj.noteFachpraxis === "undefined" ? null : obj.noteFachpraxis;
		if (typeof obj.istFachpraktischerTeilAusreichend === "undefined")
			 throw new Error('invalid json format, missing attribute istFachpraktischerTeilAusreichend');
		result.istFachpraktischerTeilAusreichend = obj.istFachpraktischerTeilAusreichend;
		if (!!obj.faecher) {
			for (let elem of obj.faecher) {
				result.faecher?.add(ENMBKFach.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		return result;
	}

	public static transpilerToJSON(obj : ENMBKAbschluss) : string {
		let result = '{';
		result += '"hatZulassung" : ' + obj.hatZulassung + ',';
		result += '"hatBestanden" : ' + obj.hatBestanden + ',';
		result += '"hatZulassungErweiterteBeruflicheKenntnisse" : ' + obj.hatZulassungErweiterteBeruflicheKenntnisse + ',';
		result += '"hatErworbenErweiterteBeruflicheKenntnisse" : ' + obj.hatErworbenErweiterteBeruflicheKenntnisse + ',';
		result += '"notePraktischePruefung" : ' + ((!obj.notePraktischePruefung) ? 'null' : '"' + obj.notePraktischePruefung.valueOf() + '"') + ',';
		result += '"noteKolloqium" : ' + ((!obj.noteKolloqium) ? 'null' : '"' + obj.noteKolloqium.valueOf() + '"') + ',';
		result += '"hatZulassungBerufsabschlusspruefung" : ' + obj.hatZulassungBerufsabschlusspruefung + ',';
		result += '"hatBestandenBerufsabschlusspruefung" : ' + obj.hatBestandenBerufsabschlusspruefung + ',';
		result += '"themaAbschlussarbeit" : ' + ((!obj.themaAbschlussarbeit) ? 'null' : '"' + obj.themaAbschlussarbeit.valueOf() + '"') + ',';
		result += '"istVorhandenBerufsabschlusspruefung" : ' + obj.istVorhandenBerufsabschlusspruefung + ',';
		result += '"noteFachpraxis" : ' + ((!obj.noteFachpraxis) ? 'null' : '"' + obj.noteFachpraxis.valueOf() + '"') + ',';
		result += '"istFachpraktischerTeilAusreichend" : ' + obj.istFachpraktischerTeilAusreichend + ',';
		if (!obj.faecher) {
			result += '"faecher" : []';
		} else {
			result += '"faecher" : [ ';
			for (let i : number = 0; i < obj.faecher.size(); i++) {
				let elem = obj.faecher.get(i);
				result += ENMBKFach.transpilerToJSON(elem);
				if (i < obj.faecher.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<ENMBKAbschluss>) : string {
		let result = '{';
		if (typeof obj.hatZulassung !== "undefined") {
			result += '"hatZulassung" : ' + obj.hatZulassung + ',';
		}
		if (typeof obj.hatBestanden !== "undefined") {
			result += '"hatBestanden" : ' + obj.hatBestanden + ',';
		}
		if (typeof obj.hatZulassungErweiterteBeruflicheKenntnisse !== "undefined") {
			result += '"hatZulassungErweiterteBeruflicheKenntnisse" : ' + obj.hatZulassungErweiterteBeruflicheKenntnisse + ',';
		}
		if (typeof obj.hatErworbenErweiterteBeruflicheKenntnisse !== "undefined") {
			result += '"hatErworbenErweiterteBeruflicheKenntnisse" : ' + obj.hatErworbenErweiterteBeruflicheKenntnisse + ',';
		}
		if (typeof obj.notePraktischePruefung !== "undefined") {
			result += '"notePraktischePruefung" : ' + ((!obj.notePraktischePruefung) ? 'null' : '"' + obj.notePraktischePruefung.valueOf() + '"') + ',';
		}
		if (typeof obj.noteKolloqium !== "undefined") {
			result += '"noteKolloqium" : ' + ((!obj.noteKolloqium) ? 'null' : '"' + obj.noteKolloqium.valueOf() + '"') + ',';
		}
		if (typeof obj.hatZulassungBerufsabschlusspruefung !== "undefined") {
			result += '"hatZulassungBerufsabschlusspruefung" : ' + obj.hatZulassungBerufsabschlusspruefung + ',';
		}
		if (typeof obj.hatBestandenBerufsabschlusspruefung !== "undefined") {
			result += '"hatBestandenBerufsabschlusspruefung" : ' + obj.hatBestandenBerufsabschlusspruefung + ',';
		}
		if (typeof obj.themaAbschlussarbeit !== "undefined") {
			result += '"themaAbschlussarbeit" : ' + ((!obj.themaAbschlussarbeit) ? 'null' : '"' + obj.themaAbschlussarbeit.valueOf() + '"') + ',';
		}
		if (typeof obj.istVorhandenBerufsabschlusspruefung !== "undefined") {
			result += '"istVorhandenBerufsabschlusspruefung" : ' + obj.istVorhandenBerufsabschlusspruefung + ',';
		}
		if (typeof obj.noteFachpraxis !== "undefined") {
			result += '"noteFachpraxis" : ' + ((!obj.noteFachpraxis) ? 'null' : '"' + obj.noteFachpraxis.valueOf() + '"') + ',';
		}
		if (typeof obj.istFachpraktischerTeilAusreichend !== "undefined") {
			result += '"istFachpraktischerTeilAusreichend" : ' + obj.istFachpraktischerTeilAusreichend + ',';
		}
		if (typeof obj.faecher !== "undefined") {
			if (!obj.faecher) {
				result += '"faecher" : []';
			} else {
				result += '"faecher" : [ ';
				for (let i : number = 0; i < obj.faecher.size(); i++) {
					let elem = obj.faecher.get(i);
					result += ENMBKFach.transpilerToJSON(elem);
					if (i < obj.faecher.size() - 1)
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

export function cast_de_nrw_schule_svws_core_data_enm_ENMBKAbschluss(obj : unknown) : ENMBKAbschluss {
	return obj as ENMBKAbschluss;
}
