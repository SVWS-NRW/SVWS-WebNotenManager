import { JavaObject, cast_java_lang_Object } from '../../../java/lang/JavaObject';
import { JavaLong, cast_java_lang_Long } from '../../../java/lang/JavaLong';
import { JavaString, cast_java_lang_String } from '../../../java/lang/JavaString';

export class LehrerStammdaten extends JavaObject {

	public id : number = 0;

	public kuerzel : String = "";

	public personalTyp : String = "";

	public anrede : String | null = null;

	public titel : String | null = null;

	public amtsbezeichnung : String | null = null;

	public nachname : String = "";

	public vorname : String = "";

	public geschlecht : number = 0;

	public geburtsdatum : String | null = null;

	public staatsangehoerigkeitID : String | null = null;

	public strassenname : String | null = null;

	public hausnummer : String | null = null;

	public hausnummerZusatz : String | null = null;

	public wohnortID : Number | null = null;

	public ortsteilID : Number | null = null;

	public telefon : String | null = null;

	public telefonMobil : String | null = null;

	public emailPrivat : String | null = null;

	public emailDienstlich : String | null = null;

	public foto : String | null = null;


	public constructor() {
		super();
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.data.lehrer.LehrerStammdaten'].includes(name);
	}

	public static transpilerFromJSON(json : string): LehrerStammdaten {
		const obj = JSON.parse(json);
		const result = new LehrerStammdaten();
		if (typeof obj.id === "undefined")
			 throw new Error('invalid json format, missing attribute id');
		result.id = obj.id;
		if (typeof obj.kuerzel === "undefined")
			 throw new Error('invalid json format, missing attribute kuerzel');
		result.kuerzel = obj.kuerzel;
		if (typeof obj.personalTyp === "undefined")
			 throw new Error('invalid json format, missing attribute personalTyp');
		result.personalTyp = obj.personalTyp;
		result.anrede = typeof obj.anrede === "undefined" ? null : obj.anrede;
		result.titel = typeof obj.titel === "undefined" ? null : obj.titel;
		result.amtsbezeichnung = typeof obj.amtsbezeichnung === "undefined" ? null : obj.amtsbezeichnung;
		if (typeof obj.nachname === "undefined")
			 throw new Error('invalid json format, missing attribute nachname');
		result.nachname = obj.nachname;
		if (typeof obj.vorname === "undefined")
			 throw new Error('invalid json format, missing attribute vorname');
		result.vorname = obj.vorname;
		if (typeof obj.geschlecht === "undefined")
			 throw new Error('invalid json format, missing attribute geschlecht');
		result.geschlecht = obj.geschlecht;
		result.geburtsdatum = typeof obj.geburtsdatum === "undefined" ? null : obj.geburtsdatum;
		result.staatsangehoerigkeitID = typeof obj.staatsangehoerigkeitID === "undefined" ? null : obj.staatsangehoerigkeitID;
		result.strassenname = typeof obj.strassenname === "undefined" ? null : obj.strassenname;
		result.hausnummer = typeof obj.hausnummer === "undefined" ? null : obj.hausnummer;
		result.hausnummerZusatz = typeof obj.hausnummerZusatz === "undefined" ? null : obj.hausnummerZusatz;
		result.wohnortID = typeof obj.wohnortID === "undefined" ? null : obj.wohnortID;
		result.ortsteilID = typeof obj.ortsteilID === "undefined" ? null : obj.ortsteilID;
		result.telefon = typeof obj.telefon === "undefined" ? null : obj.telefon;
		result.telefonMobil = typeof obj.telefonMobil === "undefined" ? null : obj.telefonMobil;
		result.emailPrivat = typeof obj.emailPrivat === "undefined" ? null : obj.emailPrivat;
		result.emailDienstlich = typeof obj.emailDienstlich === "undefined" ? null : obj.emailDienstlich;
		result.foto = typeof obj.foto === "undefined" ? null : obj.foto;
		return result;
	}

	public static transpilerToJSON(obj : LehrerStammdaten) : string {
		let result = '{';
		result += '"id" : ' + obj.id + ',';
		result += '"kuerzel" : ' + '"' + obj.kuerzel.valueOf() + '"' + ',';
		result += '"personalTyp" : ' + '"' + obj.personalTyp.valueOf() + '"' + ',';
		result += '"anrede" : ' + ((!obj.anrede) ? 'null' : '"' + obj.anrede.valueOf() + '"') + ',';
		result += '"titel" : ' + ((!obj.titel) ? 'null' : '"' + obj.titel.valueOf() + '"') + ',';
		result += '"amtsbezeichnung" : ' + ((!obj.amtsbezeichnung) ? 'null' : '"' + obj.amtsbezeichnung.valueOf() + '"') + ',';
		result += '"nachname" : ' + '"' + obj.nachname.valueOf() + '"' + ',';
		result += '"vorname" : ' + '"' + obj.vorname.valueOf() + '"' + ',';
		result += '"geschlecht" : ' + obj.geschlecht + ',';
		result += '"geburtsdatum" : ' + ((!obj.geburtsdatum) ? 'null' : '"' + obj.geburtsdatum.valueOf() + '"') + ',';
		result += '"staatsangehoerigkeitID" : ' + ((!obj.staatsangehoerigkeitID) ? 'null' : '"' + obj.staatsangehoerigkeitID.valueOf() + '"') + ',';
		result += '"strassenname" : ' + ((!obj.strassenname) ? 'null' : '"' + obj.strassenname.valueOf() + '"') + ',';
		result += '"hausnummer" : ' + ((!obj.hausnummer) ? 'null' : '"' + obj.hausnummer.valueOf() + '"') + ',';
		result += '"hausnummerZusatz" : ' + ((!obj.hausnummerZusatz) ? 'null' : '"' + obj.hausnummerZusatz.valueOf() + '"') + ',';
		result += '"wohnortID" : ' + ((!obj.wohnortID) ? 'null' : obj.wohnortID.valueOf()) + ',';
		result += '"ortsteilID" : ' + ((!obj.ortsteilID) ? 'null' : obj.ortsteilID.valueOf()) + ',';
		result += '"telefon" : ' + ((!obj.telefon) ? 'null' : '"' + obj.telefon.valueOf() + '"') + ',';
		result += '"telefonMobil" : ' + ((!obj.telefonMobil) ? 'null' : '"' + obj.telefonMobil.valueOf() + '"') + ',';
		result += '"emailPrivat" : ' + ((!obj.emailPrivat) ? 'null' : '"' + obj.emailPrivat.valueOf() + '"') + ',';
		result += '"emailDienstlich" : ' + ((!obj.emailDienstlich) ? 'null' : '"' + obj.emailDienstlich.valueOf() + '"') + ',';
		result += '"foto" : ' + ((!obj.foto) ? 'null' : '"' + obj.foto.valueOf() + '"') + ',';
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

	public static transpilerToJSONPatch(obj : Partial<LehrerStammdaten>) : string {
		let result = '{';
		if (typeof obj.id !== "undefined") {
			result += '"id" : ' + obj.id + ',';
		}
		if (typeof obj.kuerzel !== "undefined") {
			result += '"kuerzel" : ' + '"' + obj.kuerzel.valueOf() + '"' + ',';
		}
		if (typeof obj.personalTyp !== "undefined") {
			result += '"personalTyp" : ' + '"' + obj.personalTyp.valueOf() + '"' + ',';
		}
		if (typeof obj.anrede !== "undefined") {
			result += '"anrede" : ' + ((!obj.anrede) ? 'null' : '"' + obj.anrede.valueOf() + '"') + ',';
		}
		if (typeof obj.titel !== "undefined") {
			result += '"titel" : ' + ((!obj.titel) ? 'null' : '"' + obj.titel.valueOf() + '"') + ',';
		}
		if (typeof obj.amtsbezeichnung !== "undefined") {
			result += '"amtsbezeichnung" : ' + ((!obj.amtsbezeichnung) ? 'null' : '"' + obj.amtsbezeichnung.valueOf() + '"') + ',';
		}
		if (typeof obj.nachname !== "undefined") {
			result += '"nachname" : ' + '"' + obj.nachname.valueOf() + '"' + ',';
		}
		if (typeof obj.vorname !== "undefined") {
			result += '"vorname" : ' + '"' + obj.vorname.valueOf() + '"' + ',';
		}
		if (typeof obj.geschlecht !== "undefined") {
			result += '"geschlecht" : ' + obj.geschlecht + ',';
		}
		if (typeof obj.geburtsdatum !== "undefined") {
			result += '"geburtsdatum" : ' + ((!obj.geburtsdatum) ? 'null' : '"' + obj.geburtsdatum.valueOf() + '"') + ',';
		}
		if (typeof obj.staatsangehoerigkeitID !== "undefined") {
			result += '"staatsangehoerigkeitID" : ' + ((!obj.staatsangehoerigkeitID) ? 'null' : '"' + obj.staatsangehoerigkeitID.valueOf() + '"') + ',';
		}
		if (typeof obj.strassenname !== "undefined") {
			result += '"strassenname" : ' + ((!obj.strassenname) ? 'null' : '"' + obj.strassenname.valueOf() + '"') + ',';
		}
		if (typeof obj.hausnummer !== "undefined") {
			result += '"hausnummer" : ' + ((!obj.hausnummer) ? 'null' : '"' + obj.hausnummer.valueOf() + '"') + ',';
		}
		if (typeof obj.hausnummerZusatz !== "undefined") {
			result += '"hausnummerZusatz" : ' + ((!obj.hausnummerZusatz) ? 'null' : '"' + obj.hausnummerZusatz.valueOf() + '"') + ',';
		}
		if (typeof obj.wohnortID !== "undefined") {
			result += '"wohnortID" : ' + ((!obj.wohnortID) ? 'null' : obj.wohnortID.valueOf()) + ',';
		}
		if (typeof obj.ortsteilID !== "undefined") {
			result += '"ortsteilID" : ' + ((!obj.ortsteilID) ? 'null' : obj.ortsteilID.valueOf()) + ',';
		}
		if (typeof obj.telefon !== "undefined") {
			result += '"telefon" : ' + ((!obj.telefon) ? 'null' : '"' + obj.telefon.valueOf() + '"') + ',';
		}
		if (typeof obj.telefonMobil !== "undefined") {
			result += '"telefonMobil" : ' + ((!obj.telefonMobil) ? 'null' : '"' + obj.telefonMobil.valueOf() + '"') + ',';
		}
		if (typeof obj.emailPrivat !== "undefined") {
			result += '"emailPrivat" : ' + ((!obj.emailPrivat) ? 'null' : '"' + obj.emailPrivat.valueOf() + '"') + ',';
		}
		if (typeof obj.emailDienstlich !== "undefined") {
			result += '"emailDienstlich" : ' + ((!obj.emailDienstlich) ? 'null' : '"' + obj.emailDienstlich.valueOf() + '"') + ',';
		}
		if (typeof obj.foto !== "undefined") {
			result += '"foto" : ' + ((!obj.foto) ? 'null' : '"' + obj.foto.valueOf() + '"') + ',';
		}
		result = result.slice(0, -1);
		result += '}';
		return result;
	}

}

export function cast_de_nrw_schule_svws_core_data_lehrer_LehrerStammdaten(obj : unknown) : LehrerStammdaten {
	return obj as LehrerStammdaten;
}
