<script setup lang="ts">
import { ref, computed, reactive, onMounted, watch } from 'vue'
import { useStore } from '../store'
import axios, {AxiosResponse} from 'axios'

import MahnungIndicator from "../Components/Dashboard/MahnungIndicator.vue"
import FloskelnMenu from "../Components/Dashboard/FloskelnMenu.vue"
import NoteInput from "../Components/Dashboard/NoteInput.vue"
import TopMenu from "../Components/TopMenu.vue"
import Menubar from '../Components/Menubar.vue'
import BemerkungenIndicator from '../Components/Klassenleitung/BemerkungenIndicator.vue'
import Tooltip from "../SVWS-Server/svws-webclient/src/ui-components/ts/src/components/Tooltip.vue";
import BottomMenu from "../Components/BottomMenu.vue";
import FloskelnMenuReadOnly from "../Components/FloskelnMenuReadOnly.vue";

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
    fachbezogeneBemerkungen: string|null,
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

type selected = { schueler: leistungType }|null

let props = defineProps({
    auth: Object,
})

const store = useStore();

let state = reactive({
    selected: null,
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
    getLeistungen()
    axios.get(route('get_filters')).then((response: AxiosResponse): AxiosResponse => state.filterValues = response.data)
})

const getLeistungen = () => axios.get(route('get_leistungen')).then((response: AxiosResponse): AxiosResponse => state.leistungen = response.data)
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

let fachlehrer = ref(false)
let fachbezogeneBemerkungen = ref(true)
let mahnungen = ref(false)

watch([fachlehrer, fachbezogeneBemerkungen, mahnungen], () => drawTable());

    let columns = ref( [])

    const baseColumns = [
        { key: 'klasse', label: 'Klasse', sortable: true },
        { key: 'name', label: 'Name', sortable: true },
        { key: 'fach', label: 'Fach', sortable: true },
        { key: 'kurs', label: 'Kurs', sortable: true },
        { key: 'note', label: 'Note', sortable: false },
    ]

    const fachlehrerColumns: Array<column> = [
        { key: 'lehrer', label: 'Lehrer', sortable: true },
    ]

    const fachbezogeneBemerkungenColumns: Array<column> = [
        { key: 'fachbezogeneBemerkungen', label: 'FB', sortable: false },
    ]

    const mahnungenColumns: Array<column> = [
        { key: 'mahnung', label: 'M', sortable: false },
    ]

    const drawTable = () => {
        columns.value.length = 0
        pushTable(false, baseColumns, true)
        pushTable(fachlehrer.value, fachlehrerColumns)
        pushTable(mahnungen.value, mahnungenColumns)
        pushTable(fachbezogeneBemerkungen.value, fachbezogeneBemerkungenColumns)
    }

    const pushTable = (model: boolean, array: Array<column>, always: boolean = false): void => {
        if (model || always) array.forEach(column => columns.value.push(column))
    }

    drawTable()

    const openFloskelMenu = (selected: selected) => state.selected = selected
    const closeFloskelMenu = (): selected|null => state.selected = null
</script>

<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
                <Menubar :auth="props.auth" />
            </template>

            <template #main>
                <div class="relative flex flex-col w-full h-screen overflow-hidden bg-white">
                    <TopMenu headline="Leistungdatenübersicht" :vertical="true">
                        <span class="flex gap-3">
                            <SvwsUiCheckbox v-model="fachlehrer">Fachlehrer</SvwsUiCheckbox>
                            <SvwsUiCheckbox v-model="fachbezogeneBemerkungen">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                            <SvwsUiCheckbox v-model="mahnungen">Mahnungen</SvwsUiCheckbox>
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
                                <SvwsUiIcon v-if="row.mahnung">
                                    <i-ri-checkbox-line aria-hidden="true"></i-ri-checkbox-line>
                                </SvwsUiIcon>
                                <SvwsUiIcon v-else>
                                    <i-ri-checkbox-blank-line aria-hidden="true"></i-ri-checkbox-blank-line>
                                </SvwsUiIcon>
                            </template>
                            <template #cell-fachbezogeneBemerkungen="{ row }">
                                <BemerkungenIndicator @open="openFloskelMenu(row)" :bemerkung="Boolean(row.fachbezogeneBemerkungen)"></BemerkungenIndicator>
                            </template>
                        </SvwsUiNewTable>
                        <div class="block w-1/3"></div>
                    </div>
                    <BottomMenu></BottomMenu>
                </div>
            </template>
            <template #contentSidebar>
                <FloskelnMenuReadOnly :schueler="state.selected" @close="closeFloskelMenu" ></FloskelnMenuReadOnly>
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
