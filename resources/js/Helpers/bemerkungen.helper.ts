import { Ref } from 'vue';
import axios, { AxiosError } from 'axios';

import { FachbezogeneFloskel, Schueler, Leistung } from '../types';

import { Occurrence } from "@/Interfaces/Occurrence"; // TODO: Move these 3 to types
import { Pronoun } from "@/Interfaces/Pronoun";
import { Floskel } from "@/Interfaces/Floskel";

const pasteShortcut = (event: KeyboardEvent, bemerkung: Ref<string | null>, floskeln: Ref<any[]>): void => {
    if (event.key === 'Enter' && bemerkung.value) {
        let replacedBemerkung: string = bemerkung.value;
        event.preventDefault();

        let matches: RegExpMatchArray | null = bemerkung.value.match(/#[^\s]+/g);

        let matchedFloskeln: Floskel[] = floskeln.value.filter(
            (item: Floskel): boolean | null => matches && matches.includes(item.kuerzel)
        );

        matchedFloskeln.forEach((floskel: Floskel): string =>
            replacedBemerkung = replacedBemerkung.replace(new RegExp(floskel.kuerzel, 'g'), floskel.text)
        );

        bemerkung.value = replacedBemerkung;
    }
};

const search = (searchFilter: Ref<string>, search: string): boolean => search.toLocaleLowerCase().includes(
	searchFilter.value?.toLocaleLowerCase() ?? '';
);

const multiselect = (filter: Ref<string[]>, column: string): boolean => {
    return filter.value.length > 0 ? filter.value.includes(column) : true;
};

const formatBasedOnGender = (text: string | null, model: Schueler | Leistung): string => {
	if (!text) {
		return '';
	}

	const pattern: RegExp = /\$VORNAME\$ \$NACHNAME\$|\$VORNAME\$|\$Vorname\$|\$NACHNAME\$/;

	let pronouns: Pronoun = {
		m: 'Er',
		w: 'Sie',
	};

	let pronoun: string | null = pronouns[model.geschlecht] !== undefined ? pronouns[model.geschlecht] : null;

	let initialOccurrence: Occurrence = {
		"$vorname$ $nachname$": [model.vorname, model.nachname].join(' '),
		"$vorname$": model.vorname,
		"$nachname$": model.nachname,
	};

	let succeedingOccurrences: Occurrence = {
		"$vorname$ $nachname$": pronoun ?? model.vorname,
		"$vorname$": pronoun ?? model.vorname,
		"$nachname$": null
	};

	return text.replace(new RegExp(pattern,"i"), (matched: string): string => {
		return initialOccurrence[matched.toLowerCase()];
	})
	.replaceAll(new RegExp(pattern ,"gi"), (matched: string): string => {
		return succeedingOccurrences[matched.toLowerCase()];
	});
}

const tableFilter = (
	floskel: FachbezogeneFloskel,
	column: string,
	value: Ref<Number>,
	containsOnlyEmptyOption: boolean = false,
): boolean => {
	if (containsOnlyEmptyOption && value.value == null) {
		return floskel[column] == null;
	}

	if (value.value == 0) {
		return true;
	}

	return floskel[column] == value.value['index'];
};

const closeEditor = (isDirty: Ref<boolean>, callback: any): void => {
	const changesNotSavedWarning: string =
        'Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren.';

	if (isDirty.value ? confirm(changesNotSavedWarning) : true) {
		if (typeof callback === 'function') {
			callback();
		}
	}
};

const addSelectedToBemerkung = (
	bemerkung: Ref<string|null>,
	selectedFloskeln: Ref<Floskel[]|FachbezogeneFloskel[]>,
): void => {
	let floskeln: string = selectedFloskeln.value.map((selected: Floskel|FachbezogeneFloskel): string => {
		return selected.text;
	}).join(' ');

	selectedFloskeln.value = [];
	bemerkung.value = [bemerkung.value, floskeln].join(' ').trim();
}

const selectFloskeln = (floskeln: Floskel[] | FachbezogeneFloskel[], selectedFloskeln: Ref<Floskel[]>): void => {
	selectedFloskeln.value = [];
	floskeln.forEach((floskel: any): Number => selectedFloskeln.value.push(floskel));
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
		storedBemerkung.value = bemerkung.value;
		isDirty.value = false;
		if (typeof callback === 'function') {
			callback();
		}
	})
	.catch((error: AxiosError): void => {
		alert('Speichern nicht möglich!');
		console.log(error);
	});

export {
    search,
	tableFilter,
    formatBasedOnGender,
	closeEditor,
    addSelectedToBemerkung,
	selectFloskeln,
	saveBemerkung,
    pasteShortcut,
    multiselect,
}
