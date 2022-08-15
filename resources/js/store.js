import { defineStore } from 'pinia'

export const useStore = defineStore('main', {
    state: () => ({
        leistungen: [],
		selectedLeistung: null,
        noten: [],
        sidebarCollapsed: true,
        progress: 0,
        bemerkungen: {
            leistung: null,
            floskelgruppe: null,
        },
    }),
    actions: {
        openBemerkungMenu(leistung, floskelgruppe) {
            this.bemerkungen = {
                leistung: leistung,
                floskelgruppe: floskelgruppe,
            };
        },
        updateLeistungMahnung(leistung, istGemahnt) {
            console.log(istGemahnt)
            this.leistungen.find(current => current.id === leistung.id).istGemahnt = istGemahnt;
        },
        updateLeistungBemerkung(leistung, floskelgruppe, bemerkung) {
            this.leistungen.find(current => current.id === leistung.id)[floskelgruppe] = bemerkung.trim().length;
        },
        updateLeistungNote(leistung, note) {
            this.leistungen.find(current => current.id === leistung.id)['note'] = note;
        },
        toggleSidebar(value) {
            this.sidebarCollapsed = value;
        },
        startProgress() {
            this.progress = Math.floor(Math.random() * (90 - 50 + 1)) + 50
        },
        finishProgress() {
            this.progress = 100;
            setTimeout(() => this.progress = 0, 500);
        },
		toggleDarkMode() {
			if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
				document.documentElement.classList.remove('dark', 'theme-dark')
				localStorage.removeItem('theme')
			} else {
				document.documentElement.classList.add('dark', 'theme-dark')
				localStorage.theme = 'dark'
			}
		},
    },
})
