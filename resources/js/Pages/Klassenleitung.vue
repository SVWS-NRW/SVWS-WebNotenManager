<script setup lang="ts">

import {computed, onMounted, reactive} from 'vue'

    import { useStore } from '../store'
    import Menubar from '../Components/Menubar.vue'
    import TopMenu from "../Components/TopMenu.vue"
    import BemerkungenIndicator from "../Components/Klassenleitung/BemerkungenIndicator.vue"
    import FloskelnMenu from "../Components/Klassenleitung/FloskelnMenu.vue"

    import axios, {AxiosPromise, AxiosResponse} from 'axios'

    type column = { key: string, label: string, sortable: boolean }
    type floskel = { gruppe: string, id: number, kuerzel: string, text: string }
    type floskelgruppe = { kuerzel: string, floskeln: floskel }
    type selected = { schueler: schueler, floskelgruppe: String }|null
    type schueler = { vorname: string, nachname: string, asv: string|null, aue: string|null, zb: string|null }

    const store = useStore()

    type filterElement = Array<{ id: string, label: string }>
    type filterValues = { klassen: filterElement}

    let props = defineProps({
        auth: Object,
    })

    let state = reactive({
        floskelgruppen: <floskelgruppe[]> [],
        schueler: <schueler[]> [],
        selected: <selected> null,
        filterValues: <filterValues> {
            'klassen': [],
        },
    })

    const columns = <column[]>[
        {key: 'klasse', label: 'Klasse', sortable: true},
        {key: 'vorname', label: 'Vorname', sortable: true},
        {key: 'nachname', label: 'Nachname', sortable: true},
        {key: 'asv', label: 'Asv', sortable: true},
        {key: 'aue', label: 'Aue', sortable: true},
        {key: 'zb', label: 'Zb', sortable: true},
    ]

    const filters = reactive({
        search: <string> '',
        klasse: <Number|string> 0,
    })

    onMounted((): void => {
        fetchSchueler()
        fetchFloskelGruppen()
        axios.get(route('get_filters')).then((response: AxiosResponse): AxiosResponse => state.filterValues = response.data)
    })

    const filteredSchueler = computed((): Array<schueler> =>
        state.schueler.filter((schueler: schueler): boolean =>
            searchFilter(schueler) && tableFilter(schueler, 'klasse', true)
        )
    )

    const searchFilter = (schueler: schueler) => {
        if (state.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(schueler.vorname) || search(schueler.nachname)
    }

    const tableFilter = (schueler: schueler, column: string, withOnlyEmptyOption: boolean = false) => {
        if (withOnlyEmptyOption && [null, ''].includes(filters[column])) return schueler[column] == null
        if (filters[column] == 0) return true
        return schueler[column] == filters[column]
    }

    const fetchSchueler = (): AxiosPromise => axios.get(route('get_schueler')).then((res: AxiosResponse) => state.schueler = res.data)
    const fetchFloskelGruppen = (): AxiosPromise => axios.get(route('get_floskeln')).then((res: AxiosResponse) => state.floskelgruppen = res.data)
    const openFloskelMenu = (selected: selected): selected => state.selected = selected
    const closeFloskelMenu = (): selected|null => state.selected = null
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
                        <SvwsUiNewTable :data="filteredSchueler" :columns="columns" class="relative">
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