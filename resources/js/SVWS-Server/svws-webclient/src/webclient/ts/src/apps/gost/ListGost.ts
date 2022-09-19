import { GostHalbjahr, GostJahrgang } from "@svws-nrw/svws-core-ts";
import { App } from "../BaseApp";
import { BaseList } from "../BaseList";
import { ListAbiturjahrgangSchueler } from "./ListAbiturjahrgangSchueler";
import { ListKursblockungen } from "./ListKursblockungen";

export class ListGost extends BaseList<GostJahrgang> {
	protected _filter = undefined;
	protected listAbiturjahrgangSchueler: ListAbiturjahrgangSchueler;
	protected listKursblockungen: ListKursblockungen;

	public constructor(listAbiturjahrgangSchueler: ListAbiturjahrgangSchueler, listKursblockungen: ListKursblockungen) {
		super();
		this.listAbiturjahrgangSchueler = listAbiturjahrgangSchueler;
		this.listKursblockungen = listKursblockungen;
	}
	/**
	 * Aktualisiert die Liste für die Schülerauswahl
	 *
	 * @returns {Promise<void>}
	 */
	public async update_list(): Promise<void> {
		await super._update_list(() =>
			App.api.getGostAbiturjahrgaenge(App.schema)
		);
	}
	/**
	 * Aktualisiert die Liste der AbiSchüler
	 *
	 * @returns {Promise<void>}
	 */
	public async on_select(): Promise<void> {
		const abijahr = this._state.ausgewaehlt?.abiturjahr?.valueOf();
		if (!abijahr) return;
		await this.listAbiturjahrgangSchueler.update_list(abijahr);
		const hj = abijahr 
			? GostHalbjahr.getPlanungshalbjahrFromAbiturjahrSchuljahrUndHalbjahr(
					abijahr,
					App.akt_abschnitt.schuljahr,
					App.akt_abschnitt.abschnitt
				)
			: undefined
		if (hj) await this.listKursblockungen.update_list(abijahr, hj.id);
	}
}
