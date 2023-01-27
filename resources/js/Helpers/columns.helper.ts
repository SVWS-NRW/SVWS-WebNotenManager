import {Column} from '../Interfaces/Column'

const baseColumns: Array<Column> = [
	{ key: 'klasse', label: 'Klasse', sortable: true },
	{ key: 'name', label: 'Name, Vorname', sortable: true },
	{ key: 'fach', label: 'Fach', sortable: true },
	{ key: 'kurs', label: 'Kurs', sortable: true },
]

const fachbezogeneBemerkungenColumns: Array<Column> = [
	{ key: 'fachbezogeneBemerkungen', label: 'FB', sortable: true },
]

const mahnungenColumns: Array<Column> = [
	{ key: 'mahnung', label: 'Mahnung', sortable: true },
]

const fehlstundenColumns: Array<Column> = [
	{ key: 'fs', label: 'FS', sortable: true },
	{ key: 'ufs', label: 'FSU', sortable: true },
]

const teilleistungenColumns: Array<Column> = []

const fachlehrerColumns: Array<Column> = [
	{ key: 'lehrer', label: 'Lehrer', sortable: false },
]

const notenColumns: Array<Column> = [
	{ key: 'teilnoten', label: 'Teilnoten', sortable: true },
	{ key: 'note', label: 'Note', sortable: true },
]

export {
	baseColumns,
	fachbezogeneBemerkungenColumns,
	mahnungenColumns,
	fehlstundenColumns,
	teilleistungenColumns,
	fachlehrerColumns,
	notenColumns,
}