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