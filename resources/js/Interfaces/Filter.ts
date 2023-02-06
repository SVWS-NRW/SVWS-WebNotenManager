export interface Filter {
	id: string,
	label: string
}

export interface LeistungsDatenFilterValues {
	jahrgaenge: Array<Filter>,
	noten: Array<Filter>,
	klassen: Array<Filter>,
	kurse: Array<Filter>,
	faecher: Array<Filter>,
}

export interface FachbezogeneFloskelnFilterValues {
	jahrgaenge: Array<Filter>,
	niveau: Array<Filter>,
}

export interface SchuelerFilterValues {
	klassen: Array<Filter>,
}