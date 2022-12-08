export interface Leistung {
	id: number,
	klasse: string|Number|null,
	name: string,
	vorname: string,
	nachname: string,
	geschlecht: string,
	fach: string|null,
	lehrer: string,
	jahrgang: string,
	kurs: string|null,
	note: string|null,
	fachbezogeneBemerkungen: string|null,
	fs: number,
	ufs: number,
	istGemahnt: boolean,
	mahndatum: boolean
}