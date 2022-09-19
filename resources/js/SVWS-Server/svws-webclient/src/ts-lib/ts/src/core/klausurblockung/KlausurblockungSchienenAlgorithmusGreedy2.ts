import { JavaObject, cast_java_lang_Object } from '../../java/lang/JavaObject';
import { KlausurblockungSchienenAlgorithmusAbstract, cast_de_nrw_schule_svws_core_klausurblockung_KlausurblockungSchienenAlgorithmusAbstract } from '../../core/klausurblockung/KlausurblockungSchienenAlgorithmusAbstract';
import { KlausurblockungSchienenDynDaten, cast_de_nrw_schule_svws_core_klausurblockung_KlausurblockungSchienenDynDaten } from '../../core/klausurblockung/KlausurblockungSchienenDynDaten';
import { Random, cast_java_util_Random } from '../../java/util/Random';
import { JavaString, cast_java_lang_String } from '../../java/lang/JavaString';
import { Logger, cast_de_nrw_schule_svws_logger_Logger } from '../../logger/Logger';
import { System, cast_java_lang_System } from '../../java/lang/System';

export class KlausurblockungSchienenAlgorithmusGreedy2 extends KlausurblockungSchienenAlgorithmusAbstract {


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
		return "Klausuren nach Knotengrad & Schienen zufällig";
	}

	public berechne(pZeitEnde : number) : void;

	public berechne() : void;

	/**
	 * Implementation for method overloads of 'berechne'
	 */
	public berechne(__param0? : number) : void {
		if (((typeof __param0 !== "undefined") && typeof __param0 === "number")) {
			let pZeitEnde : number = __param0 as number;
			this.berechne();
			this._dynDaten.aktionZustand1Speichern();
			while (System.currentTimeMillis() < pZeitEnde) {
				this.berechne();
				if (this._dynDaten.gibIstBesserAlsZustand1() === true) {
					this._dynDaten.aktionZustand1Speichern();
					if (this._dynDaten.gibIstBesserAlsZustand2() === true) 
						this._dynDaten.aktionZustand2Speichern();
				} else {
					this._dynDaten.aktionZustand1Laden();
				}
			}
		} else if ((typeof __param0 === "undefined")) {
			this._dynDaten.aktionKlausurenAusSchienenEntfernen();
			for (let klausurNr of this._dynDaten.gibErzeugeKlausurenMitHoeheremGradZuerstEtwasPermutiert()) 
				this._dynDaten.aktionSetzeKlausurInZufaelligeSchieneOderErzeugeNeue(klausurNr);
		} else throw new Error('invalid method overload');
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.klausurblockung.KlausurblockungSchienenAlgorithmusGreedy2', 'de.nrw.schule.svws.core.klausurblockung.KlausurblockungSchienenAlgorithmusAbstract'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_klausurblockung_KlausurblockungSchienenAlgorithmusGreedy2(obj : unknown) : KlausurblockungSchienenAlgorithmusGreedy2 {
	return obj as KlausurblockungSchienenAlgorithmusGreedy2;
}
