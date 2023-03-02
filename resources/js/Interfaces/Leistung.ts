import { Schueler } from './Schueler'

export interface Leistung {
	id: number,
	klasse: string|Number|null,
	name: string,
	vorname: string,
	nachname: string,
	geschlecht: string,
	fach: string|null,
	fach_id: number|null,
	lehrer: string,
	jahrgang: string,
	kurs: string|null,
	note: string|null,
	fachbezogeneBemerkungen: string|null,
	fehlstundenGesamt: Number,
	fehlstundenUnentschuldigt: Number,
	fs: number,
	ufs: number,
	istGemahnt: boolean,
	mahndatum: string,
	schueler: Schueler
}