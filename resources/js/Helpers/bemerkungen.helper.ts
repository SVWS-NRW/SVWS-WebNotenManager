import { Ref } from 'vue'
import axios, { AxiosError } from 'axios'

import {
	Floskel,
	FachbezogeneFloskel,
	Schueler,
	Leistung,
} from '../types'

import {Occurrence} from "@/Interfaces/Occurrence";
import {Pronoun} from "@/Interfaces/Pronoun";

const floskelPasteShortcut = (
    event: KeyboardEvent,
    bemerkung: Ref<string | null>,
    floskeln: Ref<any[]>
): string|null => {
    if (event.key === 'Enter' && bemerkung.value) {
        let replacedBemerkung: string = bemerkung.value
        event.preventDefault()

        let matches: RegExpMatchArray | null = bemerkung.value.match(/#[^\s]+/g)

        let matchedFloskeln: Floskel[] = floskeln.value.filter(
            (item: Floskel): boolean | null => matches && matches.includes(item.kuerzel)
        )

        matchedFloskeln.forEach((floskel: Floskel): string =>
            replacedBemerkung = replacedBemerkung.replace(new RegExp(floskel.kuerzel, 'g'), floskel.text)
        )

        return replacedBemerkung
    }

    return bemerkung.value
}

const searchFilter = (floskel: Floskel | FachbezogeneFloskel, searchTerm: string): boolean => {
	if (searchTerm === '') {
		return true
	}

	const search = (search: string): boolean => search.toLowerCase().includes(searchTerm.toLowerCase())

	return search(floskel.kuerzel) || search(floskel.text)
}

const formatStringBasedOnGender = (text: string | null, schueler: Schueler | Leistung): string => {
	if (!text) return ''

	const pattern: RegExp = /\$VORNAME\$ \$NACHNAME\$|\$VORNAME\$|\$Vorname\$|\$NACHNAME\$/

	let pronouns: Pronoun = {
		m: 'Er',
		w: 'Sie',
	}

	let pronoun: string | null = pronouns[schueler.geschlecht] !== undefined
		? pronouns[schueler.geschlecht]
		: null;

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

const tableFilter = (floskel: FachbezogeneFloskel, column: string, value: Ref<Number>, containsOnlyEmptyOption: boolean = false): boolean => {
	if (containsOnlyEmptyOption && value.value == null) return floskel[column] == null
	if (value.value == 0) return true
	return floskel[column] == value.value['index']
}

const closeEditor = (isDirty: Ref<boolean>, callback: any) => {
	const changesNotSavedWarning: string = 'Achtung die Änderungen sind noch nicht gespeichert! ' +
		'Diese gehen verloren, wenn Sie fortfahren.'

	if (isDirty.value ? confirm(changesNotSavedWarning) : true) {
		if (typeof callback === 'function') {
			callback()
		}
	}
}

const addSelectedFloskelnToBemerkung = (bemerkung: Ref<string|null>, selectedFloskeln: Ref<Floskel[]>): void => {
	let floskeln: string = selectedFloskeln.value.map(
		(selected: Floskel): string => selected.text
	).join(' ')

	selectedFloskeln.value = []
	bemerkung.value = [bemerkung.value, floskeln].join(' ').trim()
}

const selectFloskeln = (floskeln: Floskel[] | FachbezogeneFloskel[], selectedFloskeln: Ref<Floskel[]>): void => {
	selectedFloskeln.value = []
	floskeln.forEach((floskel: any): Number => selectedFloskeln.value.push(floskel))
}

const saveBemerkung = (
	routeName: string,
	id: number,
	data: Object,
	bemerkung: Ref<string|null>,
	storedBemerkung: Ref<string|null>,
	isDirty: Ref<boolean>,
	callback: any,
) => axios
	.post(route(routeName, id), data)
	.then((): void => {
		storedBemerkung.value = bemerkung.value
		isDirty.value = false
		if (typeof callback === 'function') {
			callback()
		}
	})
	.catch((error: AxiosError): void => {
		alert('Ein Fehler ist aufgetreten.')
		console.log(error)
	})

export {
	searchFilter,
	tableFilter,
	formatStringBasedOnGender,
	closeEditor,
	addSelectedFloskelnToBemerkung,
	selectFloskeln,
	saveBemerkung,
    floskelPasteShortcut
}
