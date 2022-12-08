export interface Auth {
	user: {
		'id': Number,
		'vorname': string,
		'nachname': string,
		'email': string,
		'klassen': Array<Object>,
	},
	administrator: boolean,
	schoolName: string,
}