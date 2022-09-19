import { App } from "../BaseApp";

import {
	GostBlockungKurs,
	GostBlockungListeneintrag,
	GostBlockungRegel,
	GostBlockungSchiene,
	GostBlockungsdaten,
	GostBlockungsdatenManager
} from "@svws-nrw/svws-core-ts";
import { BaseData } from "../BaseData";

export class DataGostKursblockung extends BaseData<
	GostBlockungsdaten,
	GostBlockungListeneintrag,
	GostBlockungsdatenManager
> {
	protected on_update(daten: Partial<GostBlockungsdaten>): void {
		return void daten;
	}
	
	/**
	 * Wird bei einer Änderung des ausgewählten Listeneintrags aufgerufen und holt
	 * die Daten vom SVWS-Server.
	 *
	 * @returns {Promise<GostBlockungsdaten>} Die Daten als Promise
	 */
	public async on_select(): Promise<GostBlockungsdaten | undefined> {
		if (!this.selected_list_item) return super.unselect();
		const ergebnis = await super._select((eintrag: GostBlockungListeneintrag) =>
			App.api.getGostBlockung(App.schema, eintrag.id)
		);
		if (ergebnis && App.apps.gost.dataFaecher.manager)
			this.manager = new GostBlockungsdatenManager(ergebnis, App.apps.gost.dataFaecher.manager)
		else this.manager = undefined
		return ergebnis
	}

	/**
	 * Aktualisiert die übergebenen Felder der Daten mit dem übergebenen Objekt.
	 * Ruft ggf. einen Callback bei Änderungen an den Daten auf, so dass eine
	 * Applikation auf eine tatsächliche Änderung auf diese reagieren kann (z.B.
	 * Aktualisierung von Auswahllisten zusätzlich zu den Daten, etc.).
	 *
	 * @param {Partial<GostBlockungsdaten>} data Die Daten, die aktualisiert
	 *   werden sollen
	 * @returns {Promise<boolean>} True, wenn die Daten aktualisiert wurden, sonst false
	 */
	public async patch(data: Partial<GostBlockungsdaten>): Promise<boolean> {
		const daten = this._daten;
		if (!daten || daten.id === null) return false;
		const id = daten.id;
		console.log("patch", data, id);
		return this._patch(data, () =>
			App.api.patchGostBlockung(
				data as GostBlockungsdaten,
				App.schema,
				id
			)
		);
	}

	/**Ergänzt einen Kurs in der Blockung für das angegebene fach_id
	 * @param {number} fach_id
	 * @param {number} kursart_id
	 * @returns {Promise<GostBlockungKurs|undefined>} Ein Kursobjekt bei Erfolg
	 */
	public async add_blockung_kurse(
		fach_id: number,
		kursart_id: number
	): Promise<GostBlockungKurs | undefined> {
		if (!this.daten?.id) return;
		const kurs = await App.api.addGostBlockungKurs(
			App.schema,
			this.daten.id,
			fach_id,
			kursart_id
		);
		this.manager?.addKurs(kurs)
		return kurs
	}
	/**Löscht einen Kurs in der Blockung für das angegebene fach_id
	 * @param {number} fach_id
	 * @param {number} kursart_id
	 * @returns {Promise<GostBlockungKurs|undefined>} Ein Kursobjekt bei Erfolg
	 */
	public async del_blockung_kurse(
		fach_id: number,
		kursart_id: number
	): Promise<GostBlockungKurs | undefined> {
		if (!this.daten?.id) return;
		const kurs = await App.api.deleteGostBlockungKurs(
			App.schema,
			this.daten.id,
			fach_id,
			kursart_id
		);
		this.manager?.removeKurs(kurs)
		return kurs
	}

	/**Ergänzt eine Regel in der Blockung
	 * @param {number} regel_typ
	 * @returns {Promise<GostBlockungRegel|undefined>} Ein Kursobjekt bei Erfolg
	 */
	public async add_blockung_regel(
		regel_typ: number,
	): Promise<GostBlockungRegel | undefined> {
		if (!this.daten?.id) return;
		const regel = await App.api.addGostBlockungRegel(
			App.schema,
			this.daten.id,
			regel_typ
		);
		return regel
	}
	/**Löscht eine Regel in der Blockung mit der jeweiligen id
	 * @param {number} regel_id
	 * @returns {Promise<GostBlockungRegel|undefined>} Ein Kursobjekt bei Erfolg
	 */
	public async del_blockung_regel(
		regel_id: number,
	): Promise<GostBlockungRegel | undefined> {
		if (!this.daten?.id) return;
		const regel = await App.api.deleteGostBlockungRegelByID(
			App.schema,
			regel_id
		);
		if (regel) this.manager?.removeRegel(regel)
		return regel
	}
		/**passt die Regel einer Blockung an */
		public async patch_blockung_regel(
			data: GostBlockungRegel
		): Promise<void> {
			return await App.api.patchGostBlockungRegel(
				data,
				App.schema,
				data.id
			);
		}

	/**Ergänzt eine Schiene in der Blockung für das angegebene
	 * @returns {Promise<GostBlockungSchiene|undefined>} Ein Schienenobjekt bei Erfolg
	 */	
	public async add_blockung_schiene(): Promise<GostBlockungSchiene | undefined> {
		if (!this.daten?.id) return;
		const schiene = await App.api.addGostBlockungSchiene(
			App.schema,
			this.daten.id,
		);
		this.manager?.addSchiene(schiene)
		return schiene
	}

	/**Entfernt eine Schiene aus der Blockung
	 * @returns {Promise<GostBlockungSchiene|undefined>} Ein Schienenobjekt bei Erfolg
	 */
	public async del_blockung_schiene(): Promise<GostBlockungSchiene | undefined> {
		if (!this.daten?.id) return;
		const schiene = await App.api.deleteGostBlockungSchiene(
			App.schema,
			this.daten.id,
		);
		this.manager?.removeSchiene(schiene)
		return schiene
	}

	/** passt eine Schiene an */
	public async patch_schiene(
		data: GostBlockungSchiene
	): Promise<void> {
		return await App.api.patchGostBlockungSchiene(
			data,
			App.schema,
			data.id
		);
	}
	/** passt einen Kurs an */
	public async patch_kurs(data: GostBlockungKurs): Promise<void> {
		return await App.api.patchGostBlockungKurs(
			data,
			App.schema,
			data.id
		);
	}
}
