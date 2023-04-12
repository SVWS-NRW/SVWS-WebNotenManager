export interface Settings {
	general: {
		name: string,
		address: string,
		email: string,

		hosting_provider_address: string,
		hosting_provider_name: string,

		board_address: string,
		board_contact: string,
		board_name: string,

		gdpr_address: string,
		gdpr_email: string,

		management_email: string,
		management_name: string,
		management_telephone: string,
	},
	matrix: {
		lehrer_can_override_note: boolean,
	},
	filters: {
		mein_unterricht_teilleistungen: boolean,
		mein_unterricht_mahnungen: boolean,
		mein_unterricht_fehlstunden: boolean,
		mein_unterricht_bemerkungen: boolean,

		leistungdatenuebersicht_teilleistungen: boolean,
		leistungdatenuebersicht_fachlehrer: boolean,
		leistungdatenuebersicht_mahnungen: boolean,
		leistungdatenuebersicht_bemerkungen: boolean,

	},
}