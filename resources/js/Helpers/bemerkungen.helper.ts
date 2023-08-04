import { Ref } from 'vue'
import axios, { AxiosError } from 'axios'

import {
	Floskel,
	FachbezogeneFloskel,
	Schueler,
	Leistung,
	Occurrence,
	Pronoun,
} from '../types'

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
	return floskel[column] == value.value
}

const closeEditor = (isDirty: Ref<boolean>, callback: void) => {
	const changesNotSavedWarning: string = 'Achtung die Änderungen sind noch nicht gespeichert! ' +
		'Diese gehen verloren, wenn Sie fortfahren.'

	if (isDirty.value ? confirm(changesNotSavedWarning) : true) {
		if (typeof callback === 'function') {
			callback()
		}
	}
}

const addSelectedFloskelnToBemerkung = (bemerkung: Ref<string>, selectedFloskeln: Ref<Floskel[]>): void => {
	let floskeln: string = selectedFloskeln.value.map(
		(selected: Floskel): string => selected.text
	).join(' ')

	selectedFloskeln.value = []
	bemerkung.value = [bemerkung.value, floskeln].join(' ').trim()
}

const selectFloskeln = (floskeln: Floskel[], selectedFloskeln: Ref<Floskel[]>): void => {
	selectedFloskeln.value = []
	floskeln.forEach((floskel: Floskel): Number => selectedFloskeln.value.push(floskel))
}

const saveBemerkung = (
	routeName: string,
	id: number,
	data: Object,
	bemerkung: Ref<string>,
	storedBemerkung: Ref<string>,
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
	saveBemerkung
}
