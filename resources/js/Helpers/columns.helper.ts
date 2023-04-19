import { Column } from '../Interfaces/Column'

const baseColumns: Array<Column> = [
	{ key: 'klasse', label: 'Klasse', sortable: true },
	{ key: 'name', label: 'Name, Vorname', sortable: true },
	{ key: 'fach', label: 'Fach', sortable: true },
	{ key: 'kurs', label: 'Kurs', sortable: true },
]

const fachbezogeneBemerkungenColumns: Array<Column> = [
	{ key: 'fachbezogeneBemerkungen', label: 'Fachbezogene Bemerkungen', sortable: true },
]

const mahnungenColumns: Array<Column> = [
	{ key: 'istGemahnt', label: 'Mahnungen', sortable: true },
]

const fehlstundenColumns: Array<Column> = [
	{ key: 'fs', label: 'Fachbezogene Fehlstunden', sortable: true },
	{ key: 'ufs', label: 'Unentschuldigte fachbezogene Fehlstunden', sortable: true },
]

const fachlehrerColumns: Array<Column> = [
	{ key: 'lehrer', label: 'Lehrer', sortable: true },
]

const teilleistungenColumns: Array<Column> = [
	{ key: 'teilnoten', label: 'Teilnoten', sortable: true },
]

const notenColumns: Array<Column> = [
	{ key: 'note', label: 'Note', sortable: true },
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