/* export interface SortTableColumns {
	name: 'name' | 'klasse' | 'fach' | 'kurs' | 'fachleher' | 'note' | 'mahnung' | 'fs' | 'fsu' | 'fachbezogeneBemerkungen'
} */

//export type SortTableColumns = 'name' | 'klasse' | 'fach' | 'kurs' | 'lehrer' | 'note' | 'fs' | 'fsu' | 'fachbezogeneBemerkungen'

export interface SortTableColumns {
	direction: boolean,
	sortBy: 'name' | 'klasse' | 'fach' | 'kurs' | 'lehrer' | 'note' | 'fs' | 'fsu' | 'fachbezogeneBemerkungen'
}