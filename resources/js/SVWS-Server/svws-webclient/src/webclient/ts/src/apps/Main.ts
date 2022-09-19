import { ApiSchema, ApiServer, List, Vector } from "@svws-nrw/svws-core-ts";
import {
	DBSchemaListeEintrag,
	Schuljahresabschnitt,
	KatalogEintrag,
	OrtKatalogEintrag,
	OrtsteilKatalogEintrag,
	ReligionKatalogEintrag
} from "@svws-nrw/svws-core-ts";

import { App } from "./BaseApp";
import { Schule } from "./schule/Schule";

import type { Apps } from "./BaseApp";
import { Schueler } from "./schueler/Schueler";
import { Faecher } from "./faecher/Faecher";
import { Gost } from "./gost/Gost";
import { Jahrgaenge } from "./jahrgaenge/Jahrgaenge";
import { Klassen } from "./klassen/Klassen";
import { Kurse } from "./kurse/Kurse";
import { Lehrer } from "./lehrer/Lehrer";
import { inject, InjectionKey, provide, reactive } from "vue";
import { BaseList } from "./BaseList";

export type Kataloge = {
	orte: OrtKatalogEintrag[];
	ortsteile: OrtsteilKatalogEintrag[];
	religionen: List<ReligionKatalogEintrag>;
	haltestellen: KatalogEintrag[];
	beschaeftigungsarten: KatalogEintrag[];
};

export class MainConfig {
	selected_app = "Schueler";
	dbSchema: DBSchemaListeEintrag | undefined = undefined;
	dbSchemata: DBSchemaListeEintrag[] = [];
	pending = true;
	isAuthenticated: boolean | undefined = undefined;
	isConnected = false;
	/** Bis uns etwas besseres einfällt, wird hier geschaut, ob die Kursblockung im Tab aktiv ist... */
	kursblockung_aktiv = new Set();
	blockung_aktiv = false;
	hasGost = false;

	/** Das aktuelle Drag & Drop - Objekt */
	drag_and_drop_data: any;

	get schemaname(): string {
		if (!this.dbSchema || !this.dbSchema.name) return "";
		return this.dbSchema.name.valueOf();
	}
	/**
	 * Der aktuell ausgewählte Abschnitt
	 *
	 * @returns {Schuljahresabschnitt}
	 */
	 get akt_abschnitt(): Schuljahresabschnitt {
		return App.akt_abschnitt;
	}
	/**
	 * Setzt den aktuellen Schuljahresabschnitt
	 *
	 * @param {Schuljahresabschnitt} abschnitt
	 */
	set akt_abschnitt(abschnitt: Schuljahresabschnitt) {
		App.akt_abschnitt = abschnitt;
		const lists = BaseList.all
		for (const l of lists) {
			l.update_list()
		}
	}
}

/**
 * Diese Klasse wird als App in vue geladen Sie steht als `this.$app` zur
 * Verfügung und sollte nur die nötigsten Informationen enthalten, die für alle
 * anderen Apps notwendig sind. Beispielsweise der aktuelle Abschnitt.
 */
export class Main {
	/** Die OpenAPI-Schnittstelle für den Zugriff auf die Konfiguration des SVWS-Servers */
	private hostname = "localhost";
	private username = "";
	private password = "";
	private api!: ApiServer;
	private api_schema!: ApiSchema; 
	private _pending: Promise<unknown>[] = [];

	// public connection = new Connection();
	public config: MainConfig = reactive(new MainConfig());

	public kataloge: Kataloge = {
		ortsteile: [],
		haltestellen: [],
		religionen: new Vector<ReligionKatalogEintrag>(),
		orte: [],
		beschaeftigungsarten: [],
	};

	/**
	 * Diese Methode überprüft, ob die aktuelle Verbindung zum SVWS-Server vorhanden ist.
	 *
	 * @returns {boolean}
	 */
	public get connected(): boolean {
		return this.config.isConnected;
	}
	/**
	 * Diese Methode prüft, ob der Benutzer angemeldet ist
	 *
	 * @returns {boolean}
	 */
	public get authenticated(): boolean | undefined {
		return this.config.isAuthenticated;
	}

	/**
	 * Versucht eine Verbindung zu dem SVWS-Server unter der angegebenen URL aufzubauen.
	 *
	 * @param {string} url Die URL unter der der SVWS-Server erreichbar sein soll
	 * @returns {Promise<void>}
	 */
	public async connectTo(url: string): Promise<void> {
		this.hostname = `https://${url || this.hostname}`;
		this.api = new ApiServer(this.hostname, this.username, this.password);
		console.log(`Connecting to ${this.hostname}`, url, this.api);
		// this.connection.server = url;
		// this.connection.initApi();
		// this.api_config = new ApiServer(this.connection.config);
		try {
			const result = await this.api.getConfigDBSchemata();
			console.log(`DB-Revision: ${result}`);
			//this.connection.setDBSchemata(true, result);
			this.config.dbSchemata = result.toArray(
				new Array<DBSchemaListeEintrag>()
			);
			if (result.size() <= 0) {
				this.config.dbSchema = undefined;
			} else {
				let tmp = undefined;
				for (const s of result) {
					if (s.isDefault) tmp = s;
				}
				if (!tmp) tmp = result.get(0);
				this.config.dbSchema = tmp;
			}
			console.log(
				`DB-Schema: ${this.config.dbSchema?.name}`,
				this.config.dbSchemata
			);
			this.config.isConnected = true;
		} catch (error) {
			{
				//this.connection.setDBSchemata(false, []);
				console.log(
					`Verbindung zum SVWS-Server unter https://${this.hostname} fehlgeschlagen.`
				);
				// switch (this.connection.connectionStep) {
				// 	case 1:
				// 		this.connection.connectionStep++;
				// 		this.connectTo(hostname);
				// 		break;
				// }
			}
		}
	}

	/**
	 * Authentifiziert den angebenen Benutzer mit dem angegebenen Kennwort.
	 *
	 * @param {string} username Der Benutzername
	 * @param {string} password Das Kennwort
	 * @returns {Promise<void>}
	 */
	public async authenticate(
		username: string,
		password: string
	): Promise<void> {
		// this.connection.username = username;
		// this.connection.password = password;
		// this.connection.initApi();
		try {
			this.api_schema = new ApiSchema(this.hostname, username, password);
			// const result = await this.api.isAlive();
			if (!this.config.dbSchema?.name) return
			const result = await this.api_schema.revision(this.config.dbSchema?.name.toString())
			// TODO verwende revision für Client Check
			console.log(`DB-Revision: ${result}`);
			this.username = username;
			this.password = password;
			await this.start_apps(); //returns Promise<boolean>
			this.config.isAuthenticated = true;
		} catch (error) {
			// TODO error z.B. loggen
			this.config.isAuthenticated = false;
			//this.schemaRevision = -1;
		}
	}
	/**
	 * Diese Methode startet alle Apps, die in dieser App enthalten sind. Holt mit
	 * einem Worker die größeren Kataloge ab.
	 *
	 * @returns {Promise<void>}
	 */
	private async start_apps(): Promise<void> {
		App.setup({
			url: this.hostname,
			username: this.username,
			password: this.password,
			schema: this.config.schemaname
		});
		App.apps = {
			schule: new Schule(),
			schueler: new Schueler(),
			kurse: new Kurse(),
			klassen: new Klassen(),
			jahrgaenge: new Jahrgaenge(),
			lehrer: new Lehrer(),
			faecher: new Faecher(),
			gost: new Gost()
		};
		await App.apps.schule.init();
		this.config.hasGost = !!App.apps.schule.schulform?.daten.hatGymOb;
		for (const a of Object.values(App.apps)) {
			const schule = a instanceof Schule
			const gost = a instanceof Gost 
			const noGost = !mainApp.config.hasGost
			if (!schule && !(gost && noGost)) {
				this._pending.push(a.init());
				this._pending.push(a.auswahl.update_list());
			}
		}
		const o = App.schema;
		this._pending.push(
			App.api
				.getKatalogOrte(o)
				.then(
					r =>
					(this.kataloge.orte = r.toArray(
						new Array<OrtKatalogEintrag>()
					))
				)
		);
		this._pending.push(
			App.api
				.getKatalogOrtsteile(o)
				.then(
					r =>
					(this.kataloge.ortsteile = r.toArray(
						new Array<OrtsteilKatalogEintrag>()
					))
				)
		);
		this._pending.push(
			App.api
				.getKatalogReligionen(o)
				.then(
					r =>
					(this.kataloge.religionen = r
					)
				)
		);
		this._pending.push(
			App.api
				.getKatalogHaltestellen(o)
				.then(
					r =>
					(this.kataloge.haltestellen = r.toArray(
						new Array<KatalogEintrag>()
					))
				)
		);
		this._pending.push(
			App.api
				.getKatalogBeschaeftigungsart(o)
				.then(
					r =>
					(this.kataloge.beschaeftigungsarten = r.toArray(
						new Array<KatalogEintrag>()
					))
				)
		);
		Promise.all(this._pending)
			.then(() => (this.config.pending = false))
			.catch(() => (this.config.pending = true))
			.finally(() => (this.config.selected_app = "Schueler"));
	}

	/**
	 * Gibt alle Apps als Objekt zurück
	 *
	 * @returns {Apps}
	 */
	get apps(): Apps {
		return App.apps;
	}

	/**
	 * Diese Methode loggt den aktuellen Benutzer aus und beendet die aktuell
	 * authentifizierte Verbindung zum SVWS-Server.
	 *
	 * @returns {Promise<void>}
	 */
	public logout(): void {
		this.config.selected_app = "Schueler";
		//this.connection = new Connection();
		this.connectTo(this.hostname);
	}
}

export const mainApp = new Main();

function requireInjection<T>(key: InjectionKey<T>, defaultValue?: T) {
	const resolved = inject(key, defaultValue);
	if (!resolved) {
		throw new Error(`${key} was not provided.`);
	}
	return resolved;
}

export const mainInjectKey = Symbol("MainInjectKey") as InjectionKey<Main>;
export function provideMainApp() {
	provide(mainInjectKey, mainApp);
}
export function injectMainApp() {
	return requireInjection(mainInjectKey);
}
