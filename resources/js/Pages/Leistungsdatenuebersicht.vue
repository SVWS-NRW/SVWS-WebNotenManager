<script setup lang="ts">
    import { ref, computed, reactive, onMounted, watch } from 'vue'
    import { useStore } from '../store'
    import axios, { AxiosPromise, AxiosResponse } from 'axios'

    import { Leistung } from '../Interfaces/Leistung'
    import { Column } from '../Interfaces/Column'
    import { Filter } from '../Interfaces/Filter'

    import TopMenu from '../Components/TopMenu.vue'
    import Menubar from '../Components/Menubar.vue'
    import BemerkungenIndicator from '../Components/Klassenleitung/BemerkungenIndicator.vue'
    import BottomMenu from '../Components/BottomMenu.vue'
    import FloskelnMenuReadOnly from '../Components/FloskelnMenuReadOnly.vue'

    const store = useStore()

    let props = defineProps({
        auth: Object,
    })

    let state = reactive({
        selected: <Leistung | null> null,
        leistungen: <Leistung[]> [],
        filterValues: <{
            jahrgaenge: Array<Filter>,
            noten: Array<Filter>,
            klassen: Array<Filter>,
            kurse: Array<Filter>,
            faecher: Array<Filter>
        }> {
            'jahrgaenge': [],
            'klassen': [],
            'kurse': [],
            'noten': [],
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
    )

    const searchFilter = (leistung: Leistung): boolean => {
        if (filters.search === '') return true
        const search = (search: string): boolean => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(leistung.vorname) || search(leistung.nachname)
    }

    const tableFilter = (leistung: Leistung, column: string, withOnlyEmptyOption: boolean = false): boolean => {
        if (withOnlyEmptyOption && [null, ''].includes(filters[column])) return leistung[column] == null
        if (filters[column] == 0) return true
        return leistung[column] == filters[column]
    }

    let fachlehrer = ref(false)
    let fachbezogeneBemerkungen = ref(true)
    let mahnungen = ref(false)

    watch([fachlehrer, fachbezogeneBemerkungen, mahnungen], (): void => drawTable())

    let columns = ref( [])

    const baseColumns: Array<Column> = [
        { key: 'klasse', label: 'Klasse', sortable: true },
        { key: 'name', label: 'Name', sortable: true },
        { key: 'fach', label: 'Fach', sortable: true },
        { key: 'kurs', label: 'Kurs', sortable: true },
        { key: 'note', label: 'Note', sortable: false },
    ]

    const fachlehrerColumns: Array<Column> = [
        { key: 'lehrer', label: 'Lehrer', sortable: true },
    ]

    const fachbezogeneBemerkungenColumns: Array<Column> = [
        { key: 'fachbezogeneBemerkungen', label: 'FB', sortable: false },
    ]

    const mahnungenColumns: Array<Column> = [
        { key: 'mahnung', label: 'M', sortable: false },
    ]

    const drawTable = (): void => {
        columns.value.length = 0
        pushTable(false, baseColumns, true)
        pushTable(fachlehrer.value, fachlehrerColumns)
        pushTable(mahnungen.value, mahnungenColumns)
        pushTable(fachbezogeneBemerkungen.value, fachbezogeneBemerkungenColumns)
    }

    const pushTable = (model: boolean, array: Array<Column>, always: boolean = false): void => {
        if (model || always) array.forEach((column: Column) => columns.value.push(column))
    }

    const openFloskelMenu = (selected: Leistung): Leistung => state.selected = selected
    const closeFloskelMenu = (): Leistung|null => state.selected = null

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
                    <TopMenu headline="Leistungsdatenübersicht" :vertical="true">
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
                <FloskelnMenuReadOnly :schueler="state.selected" @close="closeFloskelMenu"></FloskelnMenuReadOnly>
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
