import { Matrix } from "@/Interfaces/Matrix";

export interface Schueler {
	id: number,
	vorname: string,
	nachname: string,
	name: string,
	geschlecht: string,
	klasse: string,
	bemerkung: object,
	fachbezogeneBemerkungen: string,
	asv: string | null,
	aue: string | null,
	zb: string | null,
	gfs: Number,
	gfsu: Number,
    matrix: Matrix,
}
