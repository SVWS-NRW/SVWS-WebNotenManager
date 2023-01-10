export interface LoginFormData {
	form: {
		email: string,
		password: string,
		remember: boolean,
	},
	processing: boolean,
	errors: [[key: string], string] | []
}

export interface PasswordRequestFormData {
	form: {
		email: string,
		kuerzel: string,
		schulnummer: string,
	},
	processing: boolean,
	errors: {},
	successMessage: boolean,
}