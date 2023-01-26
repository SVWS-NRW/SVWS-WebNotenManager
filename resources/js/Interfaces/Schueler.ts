export interface Schueler {
	id: Number,
	vorname: string,
	nachname: string,
	name: string,
	geschlecht: string,
	bemerkung: object,
	fachbezogeneBemerkungen: string,
	asv: string | null,
	aue: string | null,
	zb: string | null,
	gfs: Number,
	gfsu: Number,
}