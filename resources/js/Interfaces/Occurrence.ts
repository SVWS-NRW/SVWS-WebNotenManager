export interface Occurrence {
	[index: string]: any,
	'$vorname$ $nachname$': string,
	'$vorname$': string,
	'$nachname$': string | null,
}