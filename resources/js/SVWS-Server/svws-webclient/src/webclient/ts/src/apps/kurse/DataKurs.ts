import { App } from "../BaseApp";

import { KursDaten, KursListeEintrag } from "@svws-nrw/svws-core-ts";
import { BaseData } from "../BaseData";

export class DataKurs extends BaseData<KursDaten, KursListeEintrag> {
	protected on_update(daten: Partial<KursDaten>): void {
		if (!this.selected_list_item) return;
		if (daten.kuerzel) this.selected_list_item.kuerzel = daten.kuerzel;
	}

	/**
	 * Wird bei einer Änderung des ausgewählten Listeneintrags aufgerufen und holt
	 * die Daten vom SVWS-Server.
	 *
	 * @returns {Promise<KursDaten>} Die Daten als Promise
	 */
	public async on_select(): Promise<KursDaten | undefined> {
		return super._select(eintrag =>
			App.api.getKurs(App.schema, eintrag.id)
		);
	}

	/**
	 * Aktualisiert die übergebenen Felder der Daten mit dem übergebenen Objekt.
	 * Ruft ggf. einen Callback bei Änderungen an den Daten auf, so dass eine
	 * Applikation auf eine tatsächliche Änderung auf diese reagieren kann (z.B.
	 * Aktualisierung von Auswahllisten zusätzlich zu den Daten, etc.).
	 *
	 * @param {Partial<KursDaten>} data Die Daten, die aktualisiert werden sollen
	 * @returns {Promise<boolean>} True, wenn die Daten aktualisiert wurden, sonst false
	 */
	public async patch(data: Partial<KursDaten>): Promise<boolean> {
		return !!data;
		// return this._patch(data, () =>
		// 	App.api.setKurs(
		// 		data,
		// 		App.schema,
		// 		this._daten?.id
		// 	)
		// );
	}
}