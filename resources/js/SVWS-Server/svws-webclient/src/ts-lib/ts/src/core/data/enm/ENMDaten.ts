import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { ENMKlasse, cast_de_nrw_schule_svws_core_data_enm_ENMKlasse } from '../../../core/data/enm/ENMKlasse';
import { ENMTeilleistungsart, cast_de_nrw_schule_svws_core_data_enm_ENMTeilleistungsart } from '../../../core/data/enm/ENMTeilleistungsart';
import { ENMFach, cast_de_nrw_schule_svws_core_data_enm_ENMFach } from '../../../core/data/enm/ENMFach';
import { ENMJahrgang, cast_de_nrw_schule_svws_core_data_enm_ENMJahrgang } from '../../../core/data/enm/ENMJahrgang';
import { ENMLerngruppe, cast_de_nrw_schule_svws_core_data_enm_ENMLerngruppe } from '../../../core/data/enm/ENMLerngruppe';
import { ENMLehrer, cast_de_nrw_schule_svws_core_data_enm_ENMLehrer } from '../../../core/data/enm/ENMLehrer';
import { ENMSchueler, cast_de_nrw_schule_svws_core_data_enm_ENMSchueler } from '../../../core/data/enm/ENMSchueler';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';
import { ENMNote, cast_de_nrw_schule_svws_core_data_enm_ENMNote } from '../../../core/data/enm/ENMNote';
import { Vector, cast_java_util_Vector } from '../../../java/util/Vector';
import { ENMFoerderschwerpunkt, cast_de_nrw_schule_svws_core_data_enm_ENMFoerderschwerpunkt } from '../../../core/data/enm/ENMFoerderschwerpunkt';
import { ENMFloskelgruppe, cast_de_nrw_schule_svws_core_data_enm_ENMFloskelgruppe } from '../../../core/data/enm/ENMFloskelgruppe';

export class ENMDaten extends JavaObject {

	public enmRevision : number = -1;

	public schulnummer : number = 0;

	public schuljahr : number = 0;

	public anzahlAbschnitte : number = 0;

	public aktuellerAbschnitt : number = 0;

	public publicKey : String | null = null;

	public lehrerID : number = 0;

	public fehlstundenEingabe : boolean = false;

	public fehlstundenSIFachbezogen : boolean = false;

	public fehlstundenSIIFachbezogen : boolean = false;

	public schulform : String | null = null;

	public mailadresse : String | null = null;

	public noten : Vector<ENMNote> = new Vector();

	public foerderschwerpunkte : Vector<ENMFoerderschwerpunkt> = new Vector();

	public jahrgaenge : Vector<ENMJahrgang> = new Vector();

	public klassen : Vector<ENMKlasse> = new Vector();

	public floskelgruppen : Vector<ENMFloskelgruppe> = new Vector();

	public lehrer : Vector<ENMLehrer> = new Vector();

	public faecher : Vector<ENMFach> = new Vector();

	public teilleistungsarten : Vector<ENMTeilleistungsart> = new Vector();

	public lerngruppen : Vector<ENMLerngruppe> = new Vector();

	public schueler : Vector<ENMSchueler> = new Vector();


	public constructor() {
		super();
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.enm.ENMDaten'].includes(name);
	}

	public static transpilerFromJSON(json : string): ENMDaten {
		const obj = JSON.parse(json);
		const result = new ENMDaten();
		if (typeof obj.enmRevision === "undefined")
			 throw new Error('invalid json format, missing attribute enmRevision');
		result.enmRevision = obj.enmRevision;
		if (typeof obj.schulnummer === "undefined")
			 throw new Error('invalid json format, missing attribute schulnummer');
		result.schulnummer = obj.schulnummer;
		if (typeof obj.schuljahr === "undefined")
			 throw new Error('invalid json format, missing attribute schuljahr');
		result.schuljahr = obj.schuljahr;
		if (typeof obj.anzahlAbschnitte === "undefined")
			 throw new Error('invalid json format, missing attribute anzahlAbschnitte');
		result.anzahlAbschnitte = obj.anzahlAbschnitte;
		if (typeof obj.aktuellerAbschnitt === "undefined")
			 throw new Error('invalid json format, missing attribute aktuellerAbschnitt');
		result.aktuellerAbschnitt = obj.aktuellerAbschnitt;
		result.publicKey = typeof obj.publicKey === "undefined" ? null : obj.publicKey;
		if (typeof obj.lehrerID === "undefined")
			 throw new Error('invalid json format, missing attribute lehrerID');
		result.lehrerID = obj.lehrerID;
		if (typeof obj.fehlstundenEingabe === "undefined")
			 throw new Error('invalid json format, missing attribute fehlstundenEingabe');
		result.fehlstundenEingabe = obj.fehlstundenEingabe;
		if (typeof obj.fehlstundenSIFachbezogen === "undefined")
			 throw new Error('invalid json format, missing attribute fehlstundenSIFachbezogen');
		result.fehlstundenSIFachbezogen = obj.fehlstundenSIFachbezogen;
		if (typeof obj.fehlstundenSIIFachbezogen === "undefined")
			 throw new Error('invalid json format, missing attribute fehlstundenSIIFachbezogen');
		result.fehlstundenSIIFachbezogen = obj.fehlstundenSIIFachbezogen;
		result.schulform = typeof obj.schulform === "undefined" ? null : obj.schulform;
		result.mailadresse = typeof obj.mailadresse === "undefined" ? null : obj.mailadresse;
		if (!!obj.noten) {
			for (let elem of obj.noten) {
				result.noten?.add(ENMNote.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		if (!!obj.foerderschwerpunkte) {
			for (let elem of obj.foerderschwerpunkte) {
				result.foerderschwerpunkte?.add(ENMFoerderschwerpunkt.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		if (!!obj.jahrgaenge) {
			for (let elem of obj.jahrgaenge) {
				result.jahrgaenge?.add(ENMJahrgang.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		if (!!obj.klassen) {
			for (let elem of obj.klassen) {
				result.klassen?.add(ENMKlasse.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		if (!!obj.floskelgruppen) {
			for (let elem of obj.floskelgruppen) {
				result.floskelgruppen?.add(ENMFloskelgruppe.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		if (!!obj.lehrer) {
			for (let elem of obj.lehrer) {
				result.lehrer?.add(ENMLehrer.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		if (!!obj.faecher) {
			for (let elem of obj.faecher) {
				result.faecher?.add(ENMFach.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		if (!!obj.teilleistungsarten) {
			for (let elem of obj.teilleistungsarten) {
				result.teilleistungsarten?.add(ENMTeilleistungsart.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		if (!!obj.lerngruppen) {
			for (let elem of obj.lerngruppen) {
				result.lerngruppen?.add(ENMLerngruppe.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		if (!!obj.schueler) {
			for (let elem of obj.schueler) {
				result.schueler?.add(ENMSchueler.transpilerFromJSON(JSON.stringify(elem)));
			}
		}
		return result;
	}

	public static transpilerToJSON(obj : ENMDaten) : string {
		let result = '{';
		result += '"enmRevision" : ' + obj.enmRevision + ',';
		result += '"schulnummer" : ' + obj.schulnummer + ',';
		result += '"schuljahr" : ' + obj.schuljahr + ',';
		result += '"anzahlAbschnitte" : ' + obj.anzahlAbschnitte + ',';
		result += '"aktuellerAbschnitt" : ' + obj.aktuellerAbschnitt + ',';
		result += '"publicKey" : ' + ((!obj.publicKey) ? 'null' : '"' + obj.publicKey.valueOf() + '"') + ',';
		result += '"lehrerID" : ' + obj.lehrerID + ',';
		result += '"fehlstundenEingabe" : ' + obj.fehlstundenEingabe + ',';
		result += '"fehlstundenSIFachbezogen" : ' + obj.fehlstundenSIFachbezogen + ',';
		result += '"fehlstundenSIIFachbezogen" : ' + obj.fehlstundenSIIFachbezogen + ',';
		result += '"schulform" : ' + ((!obj.schulform) ? 'null' : '"' + obj.schulform.valueOf() + '"') + ',';
		result += '"mailadresse" : ' + ((!obj.mailadresse) ? 'null' : '"' + obj.mailadresse.valueOf() + '"') + ',';
		if (!obj.noten) {
			result += '"noten" : []';
		} else {
			result += '"noten" : [ ';
			for (let i : number = 0; i < obj.noten.size(); i++) {
				let elem = obj.noten.get(i);
				result += ENMNote.transpilerToJSON(elem);
				if (i < obj.noten.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		if (!obj.foerderschwerpunkte) {
			result += '"foerderschwerpunkte" : []';
		} else {
			result += '"foerderschwerpunkte" : [ ';
			for (let i : number = 0; i < obj.foerderschwerpunkte.size(); i++) {
				let elem = obj.foerderschwerpunkte.get(i);
				result += ENMFoerderschwerpunkt.transpilerToJSON(elem);
				if (i < obj.foerderschwerpunkte.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		if (!obj.jahrgaenge) {
			result += '"jahrgaenge" : []';
		} else {
			result += '"jahrgaenge" : [ ';
			for (let i : number = 0; i < obj.jahrgaenge.size(); i++) {
				let elem = obj.jahrgaenge.get(i);
				result += ENMJahrgang.transpilerToJSON(elem);
				if (i < obj.jahrgaenge.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		if (!obj.klassen) {
			result += '"klassen" : []';
		} else {
			result += '"klassen" : [ ';
			for (let i : number = 0; i < obj.klassen.size(); i++) {
				let elem = obj.klassen.get(i);
				result += ENMKlasse.transpilerToJSON(elem);
				if (i < obj.klassen.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		if (!obj.floskelgruppen) {
			result += '"floskelgruppen" : []';
		} else {
			result += '"floskelgruppen" : [ ';
			for (let i : number = 0; i < obj.floskelgruppen.size(); i++) {
				let elem = obj.floskelgruppen.get(i);
				result += ENMFloskelgruppe.transpilerToJSON(elem);
				if (i < obj.floskelgruppen.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		if (!obj.lehrer) {
			result += '"lehrer" : []';
		} else {
			result += '"lehrer" : [ ';
			for (let i : number = 0; i < obj.lehrer.size(); i++) {
				let elem = obj.lehrer.get(i);
				result += ENMLehrer.transpilerToJSON(elem);
				if (i < obj.lehrer.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		if (!obj.faecher) {
			result += '"faecher" : []';
		} else {
			result += '"faecher" : [ ';
			for (let i : number = 0; i < obj.faecher.size(); i++) {
				let elem = obj.faecher.get(i);
				result += ENMFach.transpilerToJSON(elem);
				if (i < obj.faecher.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		if (!obj.teilleistungsarten) {
			result += '"teilleistungsarten" : []';
		} else {
			result += '"teilleistungsarten" : [ ';
			for (let i : number = 0; i < obj.teilleistungsarten.size(); i++) {
				let elem = obj.teilleistungsarten.get(i);
				result += ENMTeilleistungsart.transpilerToJSON(elem);
				if (i < obj.teilleistungsarten.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		if (!obj.lerngruppen) {
			result += '"lerngruppen" : []';
		} else {
			result += '"lerngruppen" : [ ';
			for (let i : number = 0; i < obj.lerngruppen.size(); i++) {
				let elem = obj.lerngruppen.get(i);
				result += ENMLerngruppe.transpilerToJSON(elem);
				if (i < obj.lerngruppen.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		if (!obj.schueler) {
			result += '"schueler" : []';
		} else {
			result += '"schueler" : [ ';
			for (let i : number = 0; i < obj.schueler.size(); i++) {
				let elem = obj.schueler.get(i);
				result += ENMSchueler.transpilerToJSON(elem);
				if (i < obj.schueler.size() - 1)
					result += ',';
			}
			result += ' ]' + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<ENMDaten>) : string {
		let result = '{';
		if (typeof obj.enmRevision !== "undefined") {
			result += '"enmRevision" : ' + obj.enmRevision + ',';
		}
		if (typeof obj.schulnummer !== "undefined") {
			result += '"schulnummer" : ' + obj.schulnummer + ',';
		}
		if (typeof obj.schuljahr !== "undefined") {
			result += '"schuljahr" : ' + obj.schuljahr + ',';
		}
		if (typeof obj.anzahlAbschnitte !== "undefined") {
			result += '"anzahlAbschnitte" : ' + obj.anzahlAbschnitte + ',';
		}
		if (typeof obj.aktuellerAbschnitt !== "undefined") {
			result += '"aktuellerAbschnitt" : ' + obj.aktuellerAbschnitt + ',';
		}
		if (typeof obj.publicKey !== "undefined") {
			result += '"publicKey" : ' + ((!obj.publicKey) ? 'null' : '"' + obj.publicKey.valueOf() + '"') + ',';
		}
		if (typeof obj.lehrerID !== "undefined") {
			result += '"lehrerID" : ' + obj.lehrerID + ',';
		}
		if (typeof obj.fehlstundenEingabe !== "undefined") {
			result += '"fehlstundenEingabe" : ' + obj.fehlstundenEingabe + ',';
		}
		if (typeof obj.fehlstundenSIFachbezogen !== "undefined") {
			result += '"fehlstundenSIFachbezogen" : ' + obj.fehlstundenSIFachbezogen + ',';
		}
		if (typeof obj.fehlstundenSIIFachbezogen !== "undefined") {
			result += '"fehlstundenSIIFachbezogen" : ' + obj.fehlstundenSIIFachbezogen + ',';
		}
		if (typeof obj.schulform !== "undefined") {
			result += '"schulform" : ' + ((!obj.schulform) ? 'null' : '"' + obj.schulform.valueOf() + '"') + ',';
		}
		if (typeof obj.mailadresse !== "undefined") {
			result += '"mailadresse" : ' + ((!obj.mailadresse) ? 'null' : '"' + obj.mailadresse.valueOf() + '"') + ',';
		}
		if (typeof obj.noten !== "undefined") {
			if (!obj.noten) {
				result += '"noten" : []';
			} else {
				result += '"noten" : [ ';
				for (let i : number = 0; i < obj.noten.size(); i++) {
					let elem = obj.noten.get(i);
					result += ENMNote.transpilerToJSON(elem);
					if (i < obj.noten.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.foerderschwerpunkte !== "undefined") {
			if (!obj.foerderschwerpunkte) {
				result += '"foerderschwerpunkte" : []';
			} else {
				result += '"foerderschwerpunkte" : [ ';
				for (let i : number = 0; i < obj.foerderschwerpunkte.size(); i++) {
					let elem = obj.foerderschwerpunkte.get(i);
					result += ENMFoerderschwerpunkt.transpilerToJSON(elem);
					if (i < obj.foerderschwerpunkte.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.jahrgaenge !== "undefined") {
			if (!obj.jahrgaenge) {
				result += '"jahrgaenge" : []';
			} else {
				result += '"jahrgaenge" : [ ';
				for (let i : number = 0; i < obj.jahrgaenge.size(); i++) {
					let elem = obj.jahrgaenge.get(i);
					result += ENMJahrgang.transpilerToJSON(elem);
					if (i < obj.jahrgaenge.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.klassen !== "undefined") {
			if (!obj.klassen) {
				result += '"klassen" : []';
			} else {
				result += '"klassen" : [ ';
				for (let i : number = 0; i < obj.klassen.size(); i++) {
					let elem = obj.klassen.get(i);
					result += ENMKlasse.transpilerToJSON(elem);
					if (i < obj.klassen.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.floskelgruppen !== "undefined") {
			if (!obj.floskelgruppen) {
				result += '"floskelgruppen" : []';
			} else {
				result += '"floskelgruppen" : [ ';
				for (let i : number = 0; i < obj.floskelgruppen.size(); i++) {
					let elem = obj.floskelgruppen.get(i);
					result += ENMFloskelgruppe.transpilerToJSON(elem);
					if (i < obj.floskelgruppen.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.lehrer !== "undefined") {
			if (!obj.lehrer) {
				result += '"lehrer" : []';
			} else {
				result += '"lehrer" : [ ';
				for (let i : number = 0; i < obj.lehrer.size(); i++) {
					let elem = obj.lehrer.get(i);
					result += ENMLehrer.transpilerToJSON(elem);
					if (i < obj.lehrer.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.faecher !== "undefined") {
			if (!obj.faecher) {
				result += '"faecher" : []';
			} else {
				result += '"faecher" : [ ';
				for (let i : number = 0; i < obj.faecher.size(); i++) {
					let elem = obj.faecher.get(i);
					result += ENMFach.transpilerToJSON(elem);
					if (i < obj.faecher.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.teilleistungsarten !== "undefined") {
			if (!obj.teilleistungsarten) {
				result += '"teilleistungsarten" : []';
			} else {
				result += '"teilleistungsarten" : [ ';
				for (let i : number = 0; i < obj.teilleistungsarten.size(); i++) {
					let elem = obj.teilleistungsarten.get(i);
					result += ENMTeilleistungsart.transpilerToJSON(elem);
					if (i < obj.teilleistungsarten.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.lerngruppen !== "undefined") {
			if (!obj.lerngruppen) {
				result += '"lerngruppen" : []';
			} else {
				result += '"lerngruppen" : [ ';
				for (let i : number = 0; i < obj.lerngruppen.size(); i++) {
					let elem = obj.lerngruppen.get(i);
					result += ENMLerngruppe.transpilerToJSON(elem);
					if (i < obj.lerngruppen.size() - 1)
						result += ',';
				}
				result += ' ]' + ',';
			}
		}
		if (typeof obj.schueler !== "undefined") {
			if (!obj.schueler) {
				result += '"schueler" : []';
			} else {
				result += '"schueler" : [ ';
				for (let i : number = 0; i < obj.schueler.size(); i++) {
					let elem = obj.schueler.get(i);
					result += ENMSchueler.transpilerToJSON(elem);
					if (i < obj.schueler.size() - 1)
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

export function cast_de_nrw_schule_svws_core_data_enm_ENMDaten(obj : unknown) : ENMDaten {
	return obj as ENMDaten;
}