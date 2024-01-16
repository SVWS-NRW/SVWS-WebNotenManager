export interface SortTableColumns {
	direction?: boolean,
	sortBy: 'name' | 'klasse' | 'fach' | 'kurs' | 'lehrer' | 'note' | 'fs' | 'fsu' | 'fachbezogeneBemerkungen' | 'gfs'
		| 'gfsu' | 'ASV' | 'AUE' | 'ZB',
}