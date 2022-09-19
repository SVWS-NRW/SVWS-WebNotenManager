<script setup lang="ts">
    import { ref, watch, computed, reactive, onMounted } from 'vue';
    import { useStore } from '../store'
    import axios from 'axios';
    import MahnungIndicator from "./MahnungIndicator.vue";
    import NoteInput from "./NoteInput.vue";
    import TopMenu from "../Components/TopMenu.vue"

    type columnType = { key: string, label: string, sortable: boolean };

    type leistungType = {

        id: number, klasse: string|null, name: string, vorname: string, nachname: string,  geschlecht: string, fach: string|null, lehrer: string, jahrgang: string,

        kurs: string|null, note: string|null, fs: number, ufs: number, istGemahnt: boolean, mahndatum: boolean
    }

    type filterElementType = Array<{ id: string, label: string }>
    type filterValuesType = {
        jahrgaenge: filterElementType,
        noten: filterElementType,
        klassen: filterElementType,
        kurse: filterElementType
    }

    const store = useStore();
    let teilleistungen = ref(true)
    let klassenleitung = ref(true)

    let state = reactive({
        leistungen: <leistungType[]> [],
        teilleistungen: <boolean>true,
        klassenleitung: <boolean>true,
        filterValues: <filterValuesType> {
            'jahrgaenge': [],
            'klassen': [],
            'kurse': [],
            'noten': [],
        },

        filters: {
            search: <string> '',
            klasse: <Number|string> '',
            jahrgang: <Number|string> '',
            kurs: <Number|string> '',
            note: <Number|string> '0',
        },
    });


    const columns = <{ key: string, label: string, sortable: boolean }[]> [
        {key: 'klasse', label: 'Klasse', sortable: true },
        {key: 'name', label: 'Name', sortable: true },
        {key: 'fach', label: 'Fach', sortable: true},
        {key: 'lehrer', label: 'Lehrer'},
        {key: 'kurs', label: 'Kurs', sortable: true},
        {key: 'note', label: 'Note', sortable: true},
        {key: 'mahnung', label: 'M', sortable: false},
        {key: 'fs', label: 'FS'},
        {key: 'ufs', label: 'uFS'},
    ]

    onMounted(() => {
        axios.get(route('get_filters')).then(response => state.filterValues = response.data)
        axios.get(route('get_leistungen')).then(response => state.leistungen = response.data)
    })

    const filteredLeistungen = computed(() =>

        state.leistungen
            .map(leistung => {
                leistung.name = [leistung.nachname, leistung.vorname].join(' ')
                return leistung
            })
            .filter(leistung =>
                searchFilter(leistung)
                && tableFilter(leistung, 'klasse', true)
                && tableFilter(leistung, 'kurs')
                && tableFilter(leistung, 'jahrgang')
                && tableFilter(leistung, 'note')
            )

    );

    const searchFilter = (leistung: leistungType) => {
        if (state.filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(state.filters.search.toLowerCase())
        return search(leistung.vorname) || search(leistung.nachname)
    }

    const tableFilter = (leistung: leistungType, column: string, withOnlyEmptyOption: boolean = false) => {
        if (withOnlyEmptyOption && state.filters[column] == '') return leistung[column] == null
        if (state.filters[column] == 0) return true
        return leistung[column] == state.filters[column]
    }


    const updateLeistungMahnung = (leistung: leistungType, istGemahnt: boolean, mahndatum: string) => {
        let current = state.leistungen.find(current => current.id === leistung.id)
        current.istGemahnt = istGemahnt
        current.mahndatum = Boolean(mahndatum)
    }

    const updateLeistungNote = (leistung, note) => {
        state.leistungen.find(current => current.id === leistung.id)['note'] = note;
    }
</script>

<template>
    <div>
        <div class="wrapper">
            <TopMenu>
                <SvwsUiCheckbox v-model="teilleistungen">Teilleistungen</SvwsUiCheckbox>
                <SvwsUiCheckbox v-model="klassenleitung">Klassenleitung</SvwsUiCheckbox>
            </TopMenu>
            <div id="filter-menu">
                <SvwsUiTextInput type="search" v-model="state.filters.search" placeholder="Suche"></SvwsUiTextInput>
                <SvwsUiSelectInput placeholder="Klasse" v-model="state.klasse" @update:value="(klasse: Number) => state.filters.klasse = klasse" :options="state.filterValues.klassen"></SvwsUiSelectInput>
                <SvwsUiSelectInput placeholder="Jahrgang" v-model="state.jahrgang" @update:value="(jahrgang: Number) => state.filters.jahrgang = jahrgang" :options="state.filterValues.jahrgaenge"></SvwsUiSelectInput>
                <SvwsUiSelectInput placeholder="Kurs" v-model="state.kurs" @update:value="(kurs: Number) => state.filters.kurs = kurs" :options="state.filterValues.kurse"></SvwsUiSelectInput>
                <SvwsUiSelectInput placeholder="Note" v-model="state.note" @update:value="(note: Number) => state.filters.note = note" :options="state.filterValues.noten"></SvwsUiSelectInput>
                <SvwsUiButton type="secondary">
                    <SvwsUiIcon>
                        <i-ri-filter-3-line aria-hidden="true"></i-ri-filter-3-line>
                    </SvwsUiIcon>
                    Erweiterte Filter
                </SvwsUiButton>
            </div>

            <div id="table" v-if="filteredLeistungen.length > 0">
                <SvwsUiNewTable :data="filteredLeistungen" :columns="columns">
                    <template #cell-mahnung="{ row }">
                        <MahnungIndicator :leistung="row" :key="row.id" @updated="updateLeistungMahnung"></MahnungIndicator>
                    </template>
                </SvwsUiNewTable>
<!--                <table>-->
<!--                    <thead>-->
<!--                        <tr>-->
<!--                            <th v-for="col in state.cols" :key="col.id" v-show="col.visible" @click="changeSort(col)">-->
<!--                                <div>-->
<!--                                    <span class="title">-->
<!--                                        {{ col.title }}-->
<!--                                    </span>-->
<!--                                    <div class="sortable" v-if="col.sortable">-->
<!--                                        <svws-ui-icon v-show="col.sortable && col.id !== state.sorting.column">-->
<!--                                            <span>Sortieren</span>-->
<!--                                            <svg fill="currentColor" viewBox="0 0 24 24">-->
<!--                                                <path fill="none" d="M0 0h24v24H0z"/>-->
<!--                                                <path d="M13 16.172l5.364-5.364 1.414 1.414L12 20l-7.778-7.778 1.414-1.414L11 16.172V4h2v12.172z"/>-->
<!--                                            </svg>-->
<!--                                        </svws-ui-icon>-->
<!--                                        <svws-ui-icon v-show="col.sortable && state.sorting.asc && col.id === state.sorting.column">-->
<!--                                            <span>Aufsteigend</span>-->
<!--                                            <svg viewBox="0 0 24 24">-->
<!--                                                <path fill="none" d="M0 0H24V24H0z"/>-->
<!--                                                <path d="M19 3l4 5h-3v12h-2V8h-3l4-5zm-5 15v2H3v-2h11zm0-7v2H3v-2h11zm-2-7v2H3V4h9z"/>-->
<!--                                            </svg>-->
<!--                                        </svws-ui-icon>-->
<!--                                        <svws-ui-icon v-show="col.sortable && !state.sorting.asc && col.id === state.sorting.column">-->
<!--                                            <span>Absteigend</span>-->
<!--                                            <svg viewBox="0 0 24 24">-->
<!--                                                <path fill="none" d="M0 0H24V24H0z"/>-->
<!--                                                <path d="M20 4v12h3l-4 5-4-5h3V4h2zm-8 14v2H3v-2h9zm2-7v2H3v-2h11zm0-7v2H3V4h11z"/>-->
<!--                                            </svg>-->
<!--                                        </svws-ui-icon>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </th>-->
<!--                        </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                        <tr v-for="leistung in filteredLeistungen" :key="leistung.id">-->
<!--                            <td>{{ leistung.klasse }}</td>-->
<!--                            <td>{{ leistung.nachname }}, {{ leistung.vorname }}</td>-->
<!--                            <td>{{ leistung.fach }}</td>-->
<!--                            <td>{{ leistung.lehrer }}</td>-->
<!--                            <td>{{ leistung.kurs }}</td>-->
<!--                            <td>-->
<!--                                <NoteInput :leistung="leistung"></NoteInput>-->
<!--                            </td>-->
<!--                            <td :class="{ danger: leistung.istGemahnt, success: leistung.mahndatum }">-->
<!--                                <MahnungIndicator :leistung="leistung" :key="leistung.id" @updated="updateLeistungMahnung"></MahnungIndicator>-->
<!--                            </td>-->
<!--                            <td v-show="klassenleitung">-->
<!--                                {{ leistung.fs }}-->
<!--                            </td>-->
<!--                            <td v-show="klassenleitung">-->
<!--                                {{ leistung.ufs }}-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                    </tbody>-->
<!--                </table>-->

            </div>

<!--            <div id="table-footer">-->
<!--                <SvwsUiCheckbox />-->
<!--                <div class="functions">-->
<!--                    <SvwsUiCheckbox>Markierungen</SvwsUiCheckbox>-->
<!--                    <SvwsUiButton type="secondary">-->
<!--                        Klassenübersicht-->
<!--                    </SvwsUiButton>-->
<!--                    <SvwsUiButton type="secondary">-->
<!--                        Notentabelle-->
<!--                    </SvwsUiButton>-->
<!--                </div>-->

<!--                <div class="info">-->
<!--                    <div id="progress-bar">-->
<!--                        <span>Neue Daten... {{ store.progress }} %</span>-->
<!--                        <div class="svws-ui-bg-dark-20">-->
<!--                            <div class="svws-ui-bg-blue" :style="{ 'width': store.progress + '%' }"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <button title="Dunkelmodus" @click="toggleDarkmode">-->
<!--                        <SvwsUiIcon>-->
<!--                            <i-ri-moon-line aria-hidden="true"></i-ri-moon-line>-->
<!--                        </SvwsUiIcon>-->
<!--                        Dark-Mode-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</template>

<style>
  #table {}
  #table-footer {}
</style>

<style scoped>
    .wrapper {
        @apply relative flex flex-col gap-6 w-full h-screen
    }

    #top-menu {
        @apply h-24 flex items-center justify-between px-6 w-full
    }

    #top-menu > .filters {
        @apply flex gap-6 items-center
    }

    #top-menu > .functions {
        @apply flex gap-3
    }

    #top-menu > .functions > .svws-ui--icon > span {
        @apply sr-only
    }

    #top-menu > .functions > .svws-ui--icon > svg  {
        @apply h-8 w-auto
    }

    #filter-menu  {
        @apply flex gap-6 px-6
    }

    #filter-menu > .svws-ui--text-input,
    #filter-menu > .svws-ui--select-input {
        @apply max-w-xs w-full
    }

    #filter-menu > button {
        @apply flex gap-2 items-center whitespace-nowrap
    }

    #filter-menu > button > svg {
        @apply h-4 w-auto
    }

    #table {
        @apply min-h-0 flex-1 overflow-auto z-10 shadow-[0_-1px_0px_0px] shadow-zinc-300 dark:shadow-zinc-600
    }

    #table > table {
        @apply w-full relative
    }

    #table > table > thead {
        box-shadow: 0 1px 0 0 rgba(192, 192, 192, 1);
        @apply sticky top-0 left-0 z-10 bg-white dark:bg-zinc-900
    }

    #table > table > thead > tr > th {
        @apply border-x border-zinc-300 dark:border-zinc-600
    }

    #table > table > thead > tr > th > div {
        @apply py-2 px-3 flex items-center gap-2
    }

    #table > table > thead > tr > th > div > .title {
        @apply text-black dark:text-zinc-300 uppercase font-bold text-sm
    }

    #table > table > thead > tr > th > div > .sortable  {
        @apply cursor-pointer
    }

    #table > table > thead > tr > th > div > .sortable > .svws-ui--icon > span  {
        @apply sr-only
    }

    #table > table > thead > tr > th > div > .sortable > .svws-ui--icon > svg  {
        @apply w-4 h-4 fill-zinc-400 dark:fill-zinc-600
    }

    #table > table > tbody > tr > td {
        @apply w-0 border py-2 px-3 text-black dark:text-zinc-300 border-zinc-300 dark:border-zinc-600
    }

    #table > table > tbody > tr > td.checkbox {
        @apply pt-4
    }

    #table > table > tbody > tr > td.danger {
        @apply bg-red-600 text-white
    }

    #table > table > tbody > tr > td.success {
        @apply bg-green-600 text-white
    }

    #table-footer {
        @apply flex justify-between gap-6 p-6 shadow-[0_-10px_15px_-5px_rgba(0,0,0,0.1)] dark:shadow-[0_-10px_15px_-5px_rgba(0,0,0,0.3)]
    }

    #table-footer > .functions {
        @apply flex gap-4
    }

    #table-footer > .info {
        @apply flex gap-4 items-end text-black dark:text-zinc-300;
    }

    #table-footer > .info > #progress-bar {
      @apply flex flex-col gap-2
    }

    #table-footer  > .info > #progress-bar > span {
        @apply text-sm
    }

    #table-footer  > .info > #progress-bar > div {
        @apply w-full rounded-full h-2.5
    }

    #table-footer  > .info > #progress-bar > div > div {
        @apply  h-2.5 rounded-full
    }

    #table-footer > .info > button {
        @apply flex gap-2 items-center justify-start font-medium text-sm  hover:underline underline-offset-2 focus-visible:outline outline-offset-2 outline-2 outline-black dark:outline-zinc-300 rounded
    }
</style>
