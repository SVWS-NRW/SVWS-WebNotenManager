<script setup lang="ts">
    import { computed, onMounted, reactive } from 'vue'
    import axios, {AxiosPromise, AxiosResponse} from 'axios'
    import { useStore } from '../store'

    import { Filter } from '../Interfaces/Filter'
    import { Schueler } from '../Interfaces/Schueler'
    import { Column } from '../Interfaces/Column'
    import { Floskelgruppe } from '../Interfaces/Floskelgruppe'

    import Menubar from '../Components/Menubar.vue'
    import TopMenu from "../Components/TopMenu.vue"
    import BemerkungenIndicator from "../Components/Klassenleitung/BemerkungenIndicator.vue"
    import FloskelnMenu from "../Components/Klassenleitung/FloskelnMenu.vue"

    type Selected = { schueler: Schueler, floskelgruppe: String } | null

    const store = useStore()

    let props = defineProps({
        auth: Object,
    })

    let state = reactive({
        floskelgruppen: <Floskelgruppe[]> [],
        schueler: <Schueler[]> [],
        selected: <Selected | null> null,
        filterValues: <{ klassen: Array<Filter>}> {'klassen': []},
    })

    const columns = <Column[]>[
        {key: 'klasse', label: 'Klasse', sortable: true},
        {key: 'vorname', label: 'Vorname', sortable: true},
        {key: 'nachname', label: 'Nachname', sortable: true},
        {key: 'asv', label: 'ASV', sortable: true},
        {key: 'aue', label: 'AUE', sortable: true},
        {key: 'zb', label: 'ZB', sortable: true},
        {key: 'gfs', label: 'gFS', sortable: true},
        {key: 'gfsu', label: 'gFSU', sortable: true},
    ]

    const filters = reactive({
        search: <string> '',
        klasse: <Number | string> 0,
    })

    onMounted((): void => {
        fetchSchueler()
        fetchFloskelGruppen()
        fetchFilters()
    })

    const fetchFilters = (): AxiosPromise => axios
        .get(route('get_filters.klassenleitung'))
        .then((response: AxiosResponse): AxiosResponse => state.filterValues = response.data)

    const filteredSchueler = computed((): Array<Schueler> =>
        state.schueler.filter((schueler: Schueler): boolean =>
            searchFilter(schueler) && tableFilter(schueler, 'klasse', true)
        )
    )

    const searchFilter = (schueler: Schueler): boolean => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(schueler.vorname) || search(schueler.nachname)
    }

    const tableFilter = (schueler: Schueler, column: string, withOnlyEmptyOption: boolean = false): boolean => {
        if (withOnlyEmptyOption && [null, ''].includes(filters[column])) return schueler[column] == null
        if (filters[column] == 0) return true
        return schueler[column] == filters[column]
    }

    const fetchSchueler = (): AxiosPromise => axios
        .get(route('get_schueler'))
        .then((res: AxiosResponse): AxiosResponse => state.schueler = res.data)

    const fetchFloskelGruppen = (): AxiosPromise => axios
        .get(route('get_floskeln'))
        .then((res: AxiosResponse): AxiosResponse => state.floskelgruppen = res.data)

    const openFloskelMenu = (selected: Selected): Selected => state.selected = selected
    const closeFloskelMenu = (): null => state.selected = null
</script>

<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
                <Menubar :auth="props.auth" />
            </template>

            <template #main>
                <div class="relative flex flex-col w-full h-screen">
                    <TopMenu headline="Klassenleitung"></TopMenu>
                    <div class="flex gap-6 px-6 relative pt-1.5 mb-6">
                        <div class="max-w-xs">
                            <SvwsUiTextInput type="search" v-model="filters.search" placeholder="Suche"></SvwsUiTextInput>
                        </div>
                        <div class="max-w-xs w-full">
                            <SvwsUiSelectInput placeholder="Klasse" v-model="filters.klasse" @update:value="(klasse: Number) => filters.klasse = klasse" :options="state.filterValues.klassen"></SvwsUiSelectInput>
                        </div>
                    </div>
                    <div class="h-full flex-1 overflow-auto">
                        <div v-if="filteredSchueler.length === 0" class="px-6">
                            <h4 class="headline-4">Keine Einträge gefunden!</h4>
                        </div>
                        <SvwsUiNewTable :data="filteredSchueler" :columns="columns" class="relative" v-if="filteredSchueler.length">
                            <template #cell-asv="{ row }">
                                <BemerkungenIndicator @open="openFloskelMenu({ schueler: row, floskelgruppe: 'asv' })" :bemerkung="Boolean(row.asv)"></BemerkungenIndicator>
                            </template>
                            <template #cell-aue="{ row }">
                                <BemerkungenIndicator @open="openFloskelMenu({ schueler: row, floskelgruppe: 'aue'})" :bemerkung="Boolean(row.aue)"></BemerkungenIndicator>
                            </template>
                            <template #cell-zb="{ row }">
                                <BemerkungenIndicator @open="openFloskelMenu({ schueler: row, floskelgruppe: 'zb' })" :bemerkung="Boolean(row.zb)"></BemerkungenIndicator>
                            </template>
                        </SvwsUiNewTable>
                    </div>
                </div>
            </template>

            <template #contentSidebar>
                <FloskelnMenu :selected="state.selected" :floskelgruppen="state.floskelgruppen" @close="closeFloskelMenu" @updated="fetchSchueler"></FloskelnMenu>
            </template>
        </SvwsUiAppLayout>
    </div>
</template>