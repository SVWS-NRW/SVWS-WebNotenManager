const tableCellEditable = (
	condition: boolean,
	administrator: boolean,
	editMode: boolean = true,
): boolean => editMode && (administrator || condition)

export {
	tableCellEditable
}