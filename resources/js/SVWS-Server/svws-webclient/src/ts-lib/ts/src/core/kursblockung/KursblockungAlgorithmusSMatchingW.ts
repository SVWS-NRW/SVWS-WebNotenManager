import { JavaObject, cast_java_lang_Object } from '../../java/lang/JavaObject';
import { KursblockungAlgorithmusS, cast_de_nrw_schule_svws_core_kursblockung_KursblockungAlgorithmusS } from '../../core/kursblockung/KursblockungAlgorithmusS';
import { KursblockungStatic, cast_de_nrw_schule_svws_core_kursblockung_KursblockungStatic } from '../../core/kursblockung/KursblockungStatic';
import { Random, cast_java_util_Random } from '../../java/util/Random';
import { KursblockungDynDaten, cast_de_nrw_schule_svws_core_kursblockung_KursblockungDynDaten } from '../../core/kursblockung/KursblockungDynDaten';
import { KursblockungDynSchueler, cast_de_nrw_schule_svws_core_kursblockung_KursblockungDynSchueler } from '../../core/kursblockung/KursblockungDynSchueler';
import { Logger, cast_de_nrw_schule_svws_logger_Logger } from '../../logger/Logger';

export class KursblockungAlgorithmusSMatchingW extends KursblockungAlgorithmusS {

	private static readonly MAX_RUNDEN_IN_FOLGE_OHNE_VERBESSERUNG : number = 5;

	private readonly schuelerArr : Array<KursblockungDynSchueler>;

	private readonly perm : Array<number>;


	/**
	 * Im Konstruktor kann die Klasse die jeweiligen Datenstrukturen aufbauen. Kurse dürfen in diese Methode noch nicht
	 * auf Schienen verteilt werden.
	 * 
	 * @param pRandom Ein {@link Random}-Objekt zur Steuerung des Zufalls über einen Anfangs-Seed.
	 * @param pLogger Logger zum Protokollieren von Warnungen und Fehlern.
	 * @param pDynDat Die dynamischen Blockungsdaten.
	 */
	public constructor(pRandom : Random, pLogger : Logger, pDynDat : KursblockungDynDaten) {
		super(pRandom, pLogger, pDynDat);
		this.schuelerArr = pDynDat.gibSchuelerArray(false);
		this.perm = KursblockungStatic.gibPermutation(this._random, this.schuelerArr.length);
	}

	/**
	 * Der Algorithmus verteilt die SuS auf ihre Kurse zufällig. Kommt es während des Verteilens zur Kollision, so wird
	 * der Kurs nicht gewählt.
	 */
	public berechne() : void {
		this.dynDaten.aktionSchuelerAusAllenKursenEntfernen();
		this.dynDaten.gibStatistik().aktionBewertungSpeichernS();
		let countKeineVerbesserung : number = 0;
		do {
			countKeineVerbesserung = this.verteileAlleSchueler() ? 0 : countKeineVerbesserung + 1;
		} while (countKeineVerbesserung < KursblockungAlgorithmusSMatchingW.MAX_RUNDEN_IN_FOLGE_OHNE_VERBESSERUNG);
	}

	/**
	 * Der Algorithmus verteilt die SuS in zufälliger Reihenfolge ein weiteres Mal zufällig. Falls die Verteilung
	 * schlechter ist, wird der vorherige Zustand wiederhergestellt.
	 * 
	 * @return TRUE, falls der Zustand sich verbessert hat.
	 */
	private verteileAlleSchueler() : boolean {
		let verbesserung : boolean = false;
		KursblockungStatic.aktionPermutiere(this._random, this.perm);
		for (let p : number = 0; p < this.schuelerArr.length; p++){
			let i : number = this.perm[p];
			verbesserung = verbesserung || this.verteileEinenSchueler(this.schuelerArr[i]);
		}
		return verbesserung;
	}

	private verteileEinenSchueler(schueler : KursblockungDynSchueler) : boolean {
		this.dynDaten.gibStatistik().aktionBewertungSpeichernS();
		schueler.aktionZustandSpeichernS();
		schueler.aktionKurseAlleEntfernen();
		schueler.aktionKurseVerteilenNurMultikurseZufaellig();
		schueler.aktionKurseVerteilenMitBipartiteMatchingGewichtetem();
		let cmp : number = this.dynDaten.gibStatistik().gibBewertungZustandS_NW_KD();
		if (cmp < 0) 
			schueler.aktionZustandLadenS();
		return cmp > 0;
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.core.kursblockung.KursblockungAlgorithmusS', 'de.nrw.schule.svws.core.kursblockung.KursblockungAlgorithmusSMatchingW'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_core_kursblockung_KursblockungAlgorithmusSMatchingW(obj : unknown) : KursblockungAlgorithmusSMatchingW {
	return obj as KursblockungAlgorithmusSMatchingW;
}