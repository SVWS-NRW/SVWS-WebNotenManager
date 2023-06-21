import { Column } from '../Interfaces/Column'

const baseColumns: Array<Column> = [
	{ key: 'klasse', label: 'Klasse', sortable: true, span: 1, minWidth: 6, disabled: true },
	{ key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 10, disabled: true },
	{ key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, disabled: true },
	{ key: 'kurs', label: 'Kurs', sortable: true, span: 2, minWidth: 5, disabled: true },
]

const fachbezogeneBemerkungenColumns: Array<Column> = [
	{ key: 'fachbezogeneBemerkungen', label: 'FB', sortable: true, span: 12, minWidth: 4 },
]

const mahnungenColumns: Array<Column> = [
	{ key: 'istGemahnt', label: 'Mahnungen', sortable: true, span: 1, minWidth: 4},
]

const fehlstundenColumns: Array<Column> = [
	{ key: 'fs', label: 'FS', sortable: true, span: 1, minWidth: 6 },
	{ key: 'fsu', label: 'FSU', sortable: true, span: 1, minWidth: 6 },
]

const fachlehrerColumns: Array<Column> = [
	{ key: 'lehrer', label: 'Lehrer', sortable: true, span: 2, minWidth: 6  },
]

const teilleistungenColumns: Array<Column> = [
	{ key: 'teilnoten', label: 'Teilnoten', sortable: true, span: 5, minWidth: 15 },
]

const notenColumns: Array<Column> = [
	{ key: 'note', label: 'Note', sortable: true, span: 1, minWidth: 5 },
]

export {
	baseColumns,
	fachbezogeneBemerkungenColumns,
	mahnungenColumns,
	fehlstundenColumns,
	fachlehrerColumns,
	notenColumns,
	teilleistungenColumns,
}