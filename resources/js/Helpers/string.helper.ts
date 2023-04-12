import { Pronoun } from '../Interfaces/Pronoun'
import { Occurrence } from '../Interfaces/Occurrence'

export interface Schueler {
	vorname: string,
	nachname: string,
	geschlecht: string,
}

const formatStringBasedOnGender = (text: string, schueler: Schueler): string => {
	if (!text) return ''

	const pattern: RegExp = /\$VORNAME\$ \$NACHNAME\$|\$VORNAME\$|\$Vorname\$|\$NACHNAME\$/

	let pronouns: Pronoun = { m: 'Er', w: 'Sie' };
	let pronoun: string|null = pronouns[schueler.geschlecht] !== undefined ? pronouns[schueler.geschlecht] : null;

	let initialOccurrence: Occurrence = {
		"$vorname$ $nachname$": [schueler.vorname, schueler.nachname].join(' '),
		"$vorname$": schueler.vorname,
		"$nachname$": schueler.nachname,
	};

	let succeedingOccurrences: Occurrence = {
		"$vorname$ $nachname$": pronoun ?? schueler.vorname,
		"$vorname$": pronoun ?? schueler.vorname,
		"$nachname$": null
	};



	return text
		.replace(new RegExp(pattern,"i"), (matched: string): string => initialOccurrence[matched.toLowerCase()])
		.replaceAll(new RegExp(pattern ,"gi"), (matched: string): string => succeedingOccurrences[matched.toLowerCase()]);
}

export { formatStringBasedOnGender };