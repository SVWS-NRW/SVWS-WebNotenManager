<script setup lang="ts">
    import { ref, computed, reactive, onMounted, watch } from 'vue'
    import { useStore } from '../store'
    import axios, {AxiosResponse} from 'axios'

    import MahnungIndicator from "../Components/MahnungIndicator.vue"
    import NoteInput from "../Components/NoteInput.vue"
    import TopMenu from "../Components/TopMenu.vue"
    import Menubar from '../Components/Menubar.vue'

    type leistungType = {
        id: number,
        klasse: string|Number|null,
        name: string,
        vorname: string,
        nachname: string,
        geschlecht: string,
        fach: string|null,
        lehrer: string,
        jahrgang: string,
        kurs: string|null,
        note: string|null,
        fs: number,
        ufs: number,
        istGemahnt: boolean,
        mahndatum: boolean
    }

    type column = {
        key: string,
        label: string,
        sortable: boolean
    }

    type filterElementType = Array<{ id: string, label: string }>
    type filterValuesType = {
        jahrgaenge: filterElementType,
        noten: filterElementType,
        klassen: filterElementType,
        kurse: filterElementType,
        faecher: filterElementType
    }

    let props = defineProps({
        auth: Object,
    })

    const store = useStore();

    let state = reactive({
        leistungen: <leistungType[]> [],
        filterValues: <filterValuesType> {
            'jahrgaenge': [],
            'klassen': [],
            'kurse': [],
            'noten': [],
        },
    })

    const filters = reactive({
        search: <string> '',
        klasse: <Number|string> 0,
        jahrgang: <Number|string> 0,
        kurs: <Number|string> 0,
        fach: <Number|string> '0',
        note: <Number|string> 0,
    })

    onMounted((): void => {
        axios.get(route('get_filters')).then((response: AxiosResponse): AxiosResponse => state.filterValues = response.data)
        axios.get(route('get_leistungen')).then((response: AxiosResponse): AxiosResponse => state.leistungen = response.data)
    })

    const filteredLeistungen = computed((): Array<leistungType> =>
        state.leistungen
            .map((leistung: leistungType): leistungType => {
                leistung.name = [leistung.nachname, leistung.vorname].join(', ')
                return leistung
            })
            .filter((leistung: leistungType): boolean =>
                searchFilter(leistung)
                && tableFilter(leistung, 'klasse', true)
                && tableFilter(leistung, 'kurs', true)
                && tableFilter(leistung, 'jahrgang')
                && tableFilter(leistung, 'note', true)
                && tableFilter(leistung, 'fach')
            )
    );

    const searchFilter = (leistung: leistungType) => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(leistung.vorname) || search(leistung.nachname)
    }

    const tableFilter = (leistung: leistungType, column: string, withOnlyEmptyOption: boolean = false) => {
        if (withOnlyEmptyOption && [null, ''].includes(filters[column])) return leistung[column] == null
        if (filters[column] == 0) return true
        return leistung[column] == filters[column]
    }

    const updateLeistungMahnung = (leistung: leistungType, istGemahnt: boolean, mahndatum: string) => {
        let current = state.leistungen.find(current => current.id === leistung.id)
        current.istGemahnt = istGemahnt
        current.mahndatum = Boolean(mahndatum)
    }

    const updateLeistungNote = (leistung: leistungType, note: string) =>
        state.leistungen.find(current => current.id === leistung.id)['note'] = note

    let teilleistungen = ref(false)
    let fachbezogeneBemerkungen = ref(false)
    let fehlstunden = ref(false)

    watch([teilleistungen, fachbezogeneBemerkungen, fehlstunden], () => drawTable());

    let columns = ref( [])

    const baseColumns = [
        { key: 'klasse', label: 'Klasse', sortable: true },
        { key: 'name', label: 'Name', sortable: true },
        { key: 'fach', label: 'Fach', sortable: true },
        { key: 'lehrer', label: 'Lehrer', sortable: true },
        { key: 'kurs', label: 'Kurs', sortable: true },
        { key: 'note', label: 'Note', sortable: true },
        { key: 'mahnung', label: 'M', sortable: false },
    ]

    const teilleistungenColumns: Array<column> = []
    const fachbezogeneBemerkungenColumns: Array<column> = []
    const fehlstundenColumns: Array<column> = [
        { key: 'fs', label: 'FS', sortable: true },
        { key: 'ufs', label: 'FSU', sortable: true },
    ]

    const drawTable = () => {
        columns.value.length = 0
        pushTable(false, baseColumns, true)
        pushTable(teilleistungen.value, teilleistungenColumns)
        pushTable(fachbezogeneBemerkungen.value, fachbezogeneBemerkungenColumns)
        pushTable(fehlstunden.value, fehlstundenColumns)
    }

    const pushTable = (model: boolean, array: Array<column>, always: boolean = false): void => {
        if (model || always) array.forEach(column => columns.value.push(column))
    }

    drawTable()
</script>

<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
                <Menubar :auth="props.auth" />
            </template>

            <template #main>
                <div class="relative flex flex-col w-full h-screen">
                    <TopMenu>
                        <SvwsUiCheckbox v-model="teilleistungen">Teilleistungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="fachbezogeneBemerkungen">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="fehlstunden">Fehlstunden</SvwsUiCheckbox>
                    </TopMenu>
                    <div id="filterMenu" class="flex gap-6 px-6 relative pt-1.5 mb-6">
                        <SvwsUiTextInput type="search" v-model="filters.search" placeholder="Suche"></SvwsUiTextInput>
                        <SvwsUiSelectInput placeholder="Klasse" v-model="filters.klasse" @update:value="(klasse: Number) => filters.klasse = klasse" :options="state.filterValues.klassen"></SvwsUiSelectInput>
                        <SvwsUiSelectInput placeholder="Jahrgang" v-model="filters.jahrgang" @update:value="(jahrgang: Number) => filters.jahrgang = jahrgang" :options="state.filterValues.jahrgaenge"></SvwsUiSelectInput>
                        <SvwsUiSelectInput placeholder="Fach" v-model="filters.fach" @update:value="(kurs: Number) => filters.fach = kurs" :options="state.filterValues.faecher"></SvwsUiSelectInput>
                        <SvwsUiSelectInput placeholder="Kurs" v-model="filters.kurs" @update:value="(kurs: Number) => filters.kurs = kurs" :options="state.filterValues.kurse"></SvwsUiSelectInput>
                        <SvwsUiSelectInput placeholder="Note" v-model="filters.note" @update:value="(note: Number) => filters.note = note" :options="state.filterValues.noten"></SvwsUiSelectInput>
                        <SvwsUiButton type="secondary" class="flex gap-2 items-center whitespace-nowrap">
                            <SvwsUiIcon>
                                <i-ri-filter-3-line aria-hidden="true"></i-ri-filter-3-line>
                            </SvwsUiIcon>
                            Erweiterte Filter
                        </SvwsUiButton>
                    </div>

                    <div class="flex-1 overflow-y-auto ">
                        <SvwsUiNewTable :data="filteredLeistungen" :columns="columns">
                            <template #cell-mahnung="{ row }">
                                <MahnungIndicator :leistung="row" :key="row.id" @updated="updateLeistungMahnung"></MahnungIndicator>
                            </template>
                            <template #cell-note="{ row }" >
                                <NoteInput :leistung="row"></NoteInput>
                            </template>
                        </SvwsUiNewTable>
                    </div>
                </div>
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