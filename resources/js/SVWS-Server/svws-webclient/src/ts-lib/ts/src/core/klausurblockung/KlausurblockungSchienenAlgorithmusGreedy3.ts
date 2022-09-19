import { JavaObject, cast_java_lang_Object } from '../../java/lang/JavaObject';
import { KlausurblockungSchienenAlgorithmusAbstract, cast_de_nrw_schule_svws_core_klausurblockung_KlausurblockungSchienenAlgorithmusAbstract } from '../../core/klausurblockung/KlausurblockungSchienenAlgorithmusAbstract';
import { KlausurblockungSchienenDynDaten, cast_de_nrw_schule_svws_core_klausurblockung_KlausurblockungSchienenDynDaten } from '../../core/klausurblockung/KlausurblockungSchienenDynDaten';
import { Random, cast_java_util_Random } from '../../java/util/Random';
import { JavaString, cast_java_lang_String } from '../../java/lang/JavaString';
import { Logger, cast_de_nrw_schule_svws_logger_Logger } from '../../logger/Logger';
import { System, cast_java_lang_System } from '../../java/lang/System';

export class KlausurblockungSchienenAlgorithmusGreedy3 extends KlausurblockungSchienenAlgorithmusAbstract {

	private minSchienen : number = 0;

	private zeitEnde : number = 0;

	private saved : boolean = false;


	/**
	 *Konstruktor.
	 * 
	 * @param pRandom   Ein {@link Random}-Objekt zur Steuerung des Zufalls über einen Anfangs-Seed.
	 * @param pLogger   Logger für Benutzerhinweise, Warnungen und Fehler.
	 * @param pDynDaten Die aktuellen Blockungsdaten. 
	 */
	public constructor(pRandom : Random, pLogger : Logger, pDynDaten : KlausurblockungSchienenDynDaten) {
		super(pRandom, pLogger, pDynDaten);
	}

	public toString() : String {
		return "Backtracking";
	}

	public berechne(pZeitEnde : number) : void {
		this.minSchienen = this._dynDaten.gibAnzahlKlausuren();
		this.zeitEnde = pZeitEnde;
		this._dynDaten.aktionKlausurenAusSchienenEntfernen();
		this.saved = false;
		this.berechneRekursiv();
		this._dynDaten.aktionZustand1Laden();
		if (this._dynDaten.gibIstBesserAlsZustand2() === true) 
			this._dynDaten.aktionZustand2Speichern();
	}

	private berechneRekursiv() : void {
		if (this._dynDaten.gibAnzahlSchienen() >= this.minSchienen) 
			return;
		if ((this.saved) && (System.currentTimeMillis() > this.zeitEnde)) 
			return;
		let klausurNr : number = this._dynDaten.gibAnzahlSchienen() === 0 ? this._dynDaten.gibKlausurDieFreiIstMitDenMeistenFreienNachbarn() : this._dynDaten.gibKlausurDieFreiIstMitDenMeistenNachbarsfarben();
		if (klausurNr < 0) {
			this.minSchienen = this._dynDaten.gibAnzahlSchienen();
			this._dynDaten.aktionZustand1Speichern();
			this.saved = true;
			return;
		}
		for (let schiene : number = 0; schiene < this.minSchienen; schiene++){
			let differenz : number = schiene < this._dynDaten.gibAnzahlSchienen() ? 0 : (schiene - this._dynDaten.gibAnzahlSchienen() + 1);
			this._dynDaten.aktionSchienenAnzahlVeraendern(+differenz);
			if (this._dynDaten.aktionSetzeKlausurInSchiene(klausurNr, schiene) === true) {
				this.berechneRekursiv();
				this._dynDaten.aktionEntferneKlausurAusSchiene(klausurNr);
			}
			this._dynDaten.aktionSchienenAnzahlVeraendern(-differenz);
		}
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.klausurblockung.KlausurblockungSchienenAlgorithmusGreedy3', 'de.nrw.schule.svws.core.klausurblockung.KlausurblockungSchienenAlgorithmusAbstract'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_klausurblockung_KlausurblockungSchienenAlgorithmusGreedy3(obj : unknown) : KlausurblockungSchienenAlgorithmusGreedy3 {
	return obj as KlausurblockungSchienenAlgorithmusGreedy3;
}
