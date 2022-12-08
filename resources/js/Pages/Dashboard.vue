<script setup lang="ts">
    import { ref, computed, reactive, onMounted, watch } from 'vue'
    import { useStore } from '../store'
    import axios, { AxiosPromise, AxiosResponse } from 'axios'

    import { Leistung } from '../Interfaces/Leistung'
    import { Column } from '../Interfaces/Column'
    import { Filter } from '../Interfaces/Filter'

    import MahnungIndicator from '../Components/Dashboard/MahnungIndicator.vue'
    import FloskelnMenu from '../Components/Dashboard/FloskelnMenu.vue'
    import NoteInput from '../Components/Dashboard/NoteInput.vue'
    import TopMenu from '../Components/TopMenu.vue'
    import Menubar from '../Components/Menubar.vue'
    import BemerkungenIndicator from '../Components/Klassenleitung/BemerkungenIndicator.vue'
    import BottomMenu from '../Components/BottomMenu.vue'

    let props = defineProps({
        auth: Object,
    })

    const store = useStore();

    let state = reactive({
        selected: <Leistung | null> null,
        leistungen: <Leistung[]> [],
        filterValues: <{
            jahrgaenge: Array<Filter>,
            noten: Array<Filter>,
            klassen: Array<Filter>,
            kurse: Array<Filter>,
            faecher: Array<Filter>,
        }> {
            'jahrgaenge': [],
            'klassen': [],
            'kurse': [],
            'noten': [],
            'faecher': [],
        },
    })

    const filters = reactive({
        search: <string> '',
        klasse: <Number | string> 0,
        jahrgang: <Number | string> 0,
        kurs: <Number | string> 0,
        fach: <Number | string> '0',
        note: <Number | string> 0,
    })

    onMounted((): void => {
        getFilters()
        getLeistungen()
    })

    const getFilters = (): AxiosPromise => axios
        .get(route('get_filters'))
        .then((response: AxiosResponse): AxiosResponse => state.filterValues = response.data)

    const getLeistungen = (): AxiosPromise => axios
        .get(route('get_leistungen'))
        .then((response: AxiosResponse): AxiosResponse => state.leistungen = response.data)

    const filteredLeistungen = computed((): Array<Leistung> => state.leistungen
        .map((leistung: Leistung): Leistung => {
            leistung.name = [leistung.nachname, leistung.vorname].join(', ')
            return leistung
        })
        .filter((leistung: Leistung): boolean =>
            searchFilter(leistung)
            && tableFilter(leistung, 'klasse', true)
            && tableFilter(leistung, 'kurs', true)
            && tableFilter(leistung, 'jahrgang')
            && tableFilter(leistung, 'note', true)
            && tableFilter(leistung, 'fach')
        )
    );

    const searchFilter = (leistung: Leistung): boolean => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(leistung.vorname) || search(leistung.nachname)
    }

    const tableFilter = (leistung: Leistung, column: string, withOnlyEmptyOption: boolean = false): boolean => {
        if (withOnlyEmptyOption && [null, ''].includes(filters[column])) return leistung[column] == null
        if (filters[column] == 0) return true
        return leistung[column] == filters[column]
    }

    const updateLeistungMahnung = (leistung: Leistung, istGemahnt: boolean, mahndatum: string): void => {
        let current = state.leistungen.find((current: Leistung) => current.id === leistung.id)
        current.istGemahnt = istGemahnt
        current.mahndatum = Boolean(mahndatum)
    }

    const updateLeistungNote = (leistung: Leistung, note: string): string =>
        state.leistungen.find((current: Leistung): boolean => current.id === leistung.id)['note'] = note

    let teilleistungen = ref(false)
    let fachbezogeneBemerkungen = ref(true)
    let mahnungen = ref(true)
    let fehlstunden = ref(false)

    watch([teilleistungen, fachbezogeneBemerkungen, fehlstunden, mahnungen], (): void => drawTable());

    let columns = ref( [])

    const baseColumns: Array<Column> = [
        { key: 'klasse', label: 'Klasse', sortable: true },
        { key: 'name', label: 'Name', sortable: true },
        { key: 'fach', label: 'Fach', sortable: true },
        { key: 'lehrer', label: 'Lehrer', sortable: true },
        { key: 'kurs', label: 'Kurs', sortable: true },
        { key: 'note', label: 'Note', sortable: false },
    ]

    const teilleistungenColumns: Array<Column> = []
    const fachbezogeneBemerkungenColumns: Array<Column> = [
        { key: 'fachbezogeneBemerkungen', label: 'FB', sortable: false },
    ]

    const mahnungenColumns: Array<Column> = [
        { key: 'mahnung', label: 'M', sortable: false },
    ]

    const fehlstundenColumns: Array<Column> = [
        { key: 'fs', label: 'FS', sortable: true },
        { key: 'ufs', label: 'FSU', sortable: true },
    ]

    const drawTable = (): void => {
        columns.value.length = 0
        pushTable(false, baseColumns, true)
        pushTable(teilleistungen.value, teilleistungenColumns)
        pushTable(mahnungen.value, mahnungenColumns)
        pushTable(fachbezogeneBemerkungen.value, fachbezogeneBemerkungenColumns)
        pushTable(fehlstunden.value, fehlstundenColumns)
    }

    const pushTable = (model: boolean, array: Array<Column>, always: boolean = false): void => {
        if (model || always) array.forEach((column: Column) => columns.value.push(column))
    }

    const openFloskelMenu = (leistung: Leistung): Leistung => state.selected = leistung
    const closeFloskelMenu = (): null => state.selected = null

    drawTable()
</script>

<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
                <Menubar :auth="props.auth" />
            </template>

            <template #main>
                <div class="relative flex flex-col w-full h-screen overflow-hidden bg-white">
                    <TopMenu headline="Mein Unterricht" :vertical="true">
                        <span class="flex gap-3">
                            <SvwsUiCheckbox v-model="teilleistungen">Teilleistungen</SvwsUiCheckbox>
                            <SvwsUiCheckbox v-model="mahnungen">Mahnungen</SvwsUiCheckbox>
                            <SvwsUiCheckbox v-model="fachbezogeneBemerkungen">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                            <SvwsUiCheckbox v-model="fehlstunden">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                        </span>
                    </TopMenu>
                    <div id="filterMenu" class="flex gap-6 px-6 relative pt-1.5 mb-6">
                        <SvwsUiTextInput type="search" v-model="filters.search" placeholder="Suche"></SvwsUiTextInput>
                        <SvwsUiSelectInput placeholder="Klasse" v-model="filters.klasse" @update:value="(klasse: Number) => filters.klasse = klasse" :options="state.filterValues.klassen"></SvwsUiSelectInput>
                        <SvwsUiSelectInput placeholder="Jahrgang" v-model="filters.jahrgang" @update:value="(jahrgang: Number) => filters.jahrgang = jahrgang" :options="state.filterValues.jahrgaenge"></SvwsUiSelectInput>
                        <SvwsUiSelectInput placeholder="Fach" v-model="filters.fach" @update:value="(kurs: Number) => filters.fach = kurs" :options="state.filterValues.faecher"></SvwsUiSelectInput>
                        <SvwsUiSelectInput placeholder="Kurs" v-model="filters.kurs" @update:value="(kurs: Number) => filters.kurs = kurs" :options="state.filterValues.kurse"></SvwsUiSelectInput>
                        <SvwsUiSelectInput placeholder="Note" v-model="filters.note" @update:value="(note: Number) => filters.note = note" :options="state.filterValues.noten"></SvwsUiSelectInput>
                    </div>

                    <div class="flex-1 flex flex-row overflow-y-auto">
                        <div v-if="filteredLeistungen.length === 0" class="px-6">
                            <h4 class="headline-4">Keine Einträge gefunden!</h4>
                        </div>
                        <SvwsUiNewTable :data="filteredLeistungen" :columns="columns" v-if="filteredLeistungen.length">
                            <template #cell-mahnung="{ row }">
                                <MahnungIndicator :leistung="row" :key="row.id" @updated="updateLeistungMahnung"></MahnungIndicator>
                            </template>
                            <template #cell-note="{ row }">
                                <NoteInput :leistung="row"></NoteInput>
                            </template>
                            <template #cell-fachbezogeneBemerkungen="{ row }">
                                <BemerkungenIndicator @open="openFloskelMenu({ leistung: row} )" :bemerkung="Boolean(row.fachbezogeneBemerkungen)"></BemerkungenIndicator>
                            </template>
                        </SvwsUiNewTable>
                        <div class="block w-1/3"></div>
                    </div>
                    <BottomMenu></BottomMenu>
                </div>
            </template>
            <template #contentSidebar>
                <FloskelnMenu :selected="state.selected" @close="closeFloskelMenu" @updated="getLeistungen()"></FloskelnMenu>
            </template>
        </SvwsUiAppLayout>
    </div>
</template>

<style scoped>
    #filterMenu .text-input,
    #filterMenu .select-input {
        @apply max-w-xs w-full
    }
</style>
