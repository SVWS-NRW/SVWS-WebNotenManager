import { Column } from '../Interfaces/Column'

const baseColumns: Array<Column> = [
	{ key: 'klasse', label: 'Klasse', sortable: true, minWidth: 6  },
	{ key: 'name', label: 'Name, Vorname', sortable: true, minWidth: 15  },
	{ key: 'fach', label: 'Fach', sortable: true, minWidth: 6 },
	{ key: 'kurs', label: 'Kurs', sortable: true, minWidth: 6 },
]

const fachbezogeneBemerkungenColumns: Array<Column> = [
	{ key: 'fachbezogeneBemerkungen', label: 'FB', sortable: true, minWidth: "50%" },
]

const mahnungenColumns: Array<Column> = [
	{ key: 'istGemahnt', label: 'Mahnungen', sortable: true, fixedWidth: 5 },
]

const fehlstundenColumns: Array<Column> = [
	{ key: 'fs', label: 'FS', sortable: true, fixedWidth: 7  },
	{ key: 'ufs', label: 'FSU', sortable: true, fixedWidth: 7  },
]

const fachlehrerColumns: Array<Column> = [
	{ key: 'lehrer', label: 'Lehrer', sortable: true, fixedWidth: 7  },
]

const teilleistungenColumns: Array<Column> = [
	{ key: 'teilnoten', label: 'Teilnoten', sortable: true, fixedWidth: 7  },
]

const notenColumns: Array<Column> = [
	{ key: 'note', label: 'Note', sortable: true, fixedWidth: 7 },
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