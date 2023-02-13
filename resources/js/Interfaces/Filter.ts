export interface Filter {

	key: string,
	index: string|number
}

export interface LeistungsDatenFilterValues {
	jahrgaenge: Array<Filter>,
	noten: Array<Filter>,
	klassen: Array<Filter>,
	kurse: Array<Filter>,
	faecher: Array<Filter>,
}

export interface FachbezogeneFloskelnFilterValues {
	jahrgaenge: Array<{	id: string, label: string}>,
	niveau: Array<{	id: string, label: string}>,
}

export interface SchuelerFilterValues {
	klassen: Array<{
		key: string,
		index: string|number
	}>,
}