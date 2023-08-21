<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import {computed, onMounted, PropType, reactive, Ref, ref, watch, provide} from 'vue'
    import axios, { AxiosPromise, AxiosResponse } from 'axios'
    import {Head, usePage} from '@inertiajs/inertia-vue3'
    import { Column } from '../Interfaces/Column'
    import { Schueler } from '../Interfaces/Schueler'
    import { Settings } from '../Interfaces/Settings'
    import { SortTableColumns } from '../Interfaces/SortTableColumns'
    import BemerkungIndicator from '../Components/BemerkungIndicator.vue'
    import TableSortButton from '../Components/TableSortButton.vue'
    import BemerkungenIndicatorReadonly from '../Components/BemerkungenIndicatorReadonly.vue'
    import FehlstundenInput from '../Components/FehlstundenInput.vue'

    import {
        SvwsUiCheckbox,
        SvwsUiSelectInput,
        SvwsUiDataTable,
        SvwsUiTextInput,
        SvwsUiContentCard,
        SvwsUiTooltip,
        SvwsUiButton,
        SvwsUiDataTableCell,
        SvwsUiDataTableRow,
    } from '@svws-nrw/svws-ui'
    import {Leistung} from '../Interfaces/Leistung'
    import BemerkungEditor from '../Components/BemerkungEditor.vue'
    import {Auth} from '../Interfaces/Auth'
    import {tableCellEditable} from '../Helpers/pages.helper'

    let props = defineProps({
        settings: {
            type: Object as PropType<Settings>,
            required: true,
        }
    })

    const title = 'Notenmanager - Klassenleitung'

    let state = reactive({
        schueler: <Schueler[]> [],
    })



    const auth: Auth = usePage().props.value.auth

    let filterOptions = <any>reactive({
        'klassen': [],
    })

    let filters = <{
        search: string,
        klasse: Number | string
    }>reactive({
        search: '',
        klasse: 0,
    })

    const columns = ref<Column[]>([])

    let tableRedrawKey: number = 0

    const drawTable = (): Column[] => { columns.value = [
            { key: 'klasse', label: 'Klasse', sortable: true, span: 1, minWidth: 6, disabled: true  },
            { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 10, disabled: true , },
            { key: 'gfs', label: 'GFS', sortable: true, span: 1, minWidth: 6, },
            { key: 'gfsu', label: 'GFSU', sortable: true, span: 1, minWidth: 6, },
            { key: 'ASV', label: 'ASV', sortable: true, span: 8, minWidth: 5, },
            { key: 'AUE', label: 'AUE', sortable: true, span: 8, minWidth: 5, },
            { key: 'ZB', label: 'ZB', sortable: true, span: 8, minWidth: 5, },
        ]
        tableRedrawKey++;
    }

    onMounted((): void => {
        drawTable()
        fetchSchueler()
    })

    const getFilters = (): void => {
        filterOptions.klassen = setFilters(state.schueler, 'klasse')
    }

    const setFilters = (data, column: string): { label: string, index: string | null | number }[] => {
        let set = [
            ...new Set(data.map((item: any): string => item[column]))
        ].map((item: string): {
            label: string, index: string | null | number
        } => {
            return { label: item ?? 'Leer', index: item }
        })

        set.unshift({ label: 'Alle', index: '0' })

        return set
    }

// TODO: make this work (add sort by under filteredSchueler, I guess)
    const sortRef: Ref<SortTableColumns> = ref({
        direction: true,
        sortBy: 'klasse'
    })

    provide('sortRef', sortRef)

    const updateSortRef = (newSortRef: SortTableColumns) => {
        sortRef.value.sortBy = newSortRef.sortBy
        sortRef.value.direction =newSortRef.direction
        drawTable()
    }

    const filteredSchueler = computed((): Array<Schueler> => state.schueler
        .sort(function(a: Leistung, b: Leistung) {
            const aSortRefSortBy = a[sortRef.value.sortBy]
            const bSortRefSortBy = b[sortRef.value.sortBy]
            if (aSortRefSortBy === null) {
                return 1;
            }

            if (aSortRefSortBy === '') {
                return 1;
            }

            if (bSortRefSortBy === null) {
                return -1;
            }

            if (bSortRefSortBy === '') {
                return -1;
            }

            let x: string | Number = aSortRefSortBy.toString();
            let y: string | Number = bSortRefSortBy.toString();

            if (x > y) {
                return sortRef.value.direction ? 1 : -1;
            }

            if (x < y) {
                return sortRef.value.direction ? -1 : 1;
            }

            return 0;
        })
        .filter((schueler: Schueler): boolean =>
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
        .get(route('api.klassenleitung'))
        .then((response: AxiosResponse): AxiosResponse => state.schueler = response.data)
        .finally((): void => getFilters())

    const selectedSchueler: Ref<Schueler | null> = ref(null)
    const selectedFloskelgruppe: Ref<string> = ref('asv')

    const selectSchueler = (schueler: Schueler, floskelgruppe?: string): void => {
        selectedSchueler.value = null
        selectedSchueler.value = schueler

        if (floskelgruppe) {
            selectedFloskelgruppe.value = floskelgruppe
        }
    }

    const valueReadonly = (schueler: Schueler, permission: 'editable_fb'): boolean => !schueler.matrix[permission]


    const editable = (condition: boolean): boolean => tableCellEditable(condition, auth.administrator) // ok
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <AppLayout title="Klassenleitung">
        <template v-slot:aside v-if="selectedSchueler">
            <BemerkungEditor
                :schueler="selectedSchueler"
                :floskelgruppe="selectedFloskelgruppe"
                @close="selectedSchueler = null"
                @updated="selectedSchueler[selectedFloskelgruppe.toUpperCase()] = $event; drawTable()"
            ></BemerkungEditor>
        </template>

        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">{{ title }}</h2>
                </div>
                <div id="filters">
                    <SvwsUiTextInput type="search" placeholder="Suche" v-model="filters.search"></SvwsUiTextInput>
<!--                    <SvwsUiSelectInput placeholder="Klasse" v-model="filters.klasse" :options="filterOptions.klassen"></SvwsUiSelectInput>-->
                </div>
            </header>

            <SvwsUiDataTable v-if="filteredSchueler.length" :noData="false" :key="tableRedrawKey" clickable>
                <template #header>
                    <SvwsUiDataTableRow thead>
                        <SvwsUiDataTableCell thead span="1" minWidth="6">
                            <TableSortButton :presentColumn= "{sortBy:'klasse'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Klasse</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead span="3" minWidth="10">
                            <TableSortButton :presentColumn= "{sortBy:'name'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Name, Vorname</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Gesamtfehlstunden" span="1" minWidth="6">
                            <TableSortButton :presentColumn= "{sortBy:'gfs'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">GFS</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Unentschuldigte Gesamtfehlstunden" span="1" minWidth="6">
                            <TableSortButton :presentColumn= "{sortBy:'gfsu'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">GFSU</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Arbeits- und Sozialverhalten" span="8" minWidth="5">
                            <TableSortButton :presentColumn= "{sortBy:'ASV'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">ASV</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Außerunterrichtliches Engagement" span="8" minWidth="5">
                            <TableSortButton :presentColumn= "{sortBy:'AUE'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">AUE</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Zeugnisbemerkung" span="8" minWidth="5">
                            <TableSortButton :presentColumn= "{sortBy:'ZB'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">ZB</TableSortButton>
                        </SvwsUiDataTableCell>
                    </SvwsUiDataTableRow>
                </template>
<!-- TODO: are span and minwidth here ok or old? -->
                <template #body="{ rows }">
                    <SvwsUiDataTableRow v-for="(row, index) in filteredSchueler" :key="index" >
                        <SvwsUiDataTableCell @click="select(row)" span="1" minWidth="6">
                            <button type="button" @click="selectSchueler(row)" class="truncate">{{ row.klasse }}</button>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell @click="select(row)" span="3" minWidth="10">
                            <button type="button" @click="selectSchueler(row)" class="truncate">{{ row.name }}</button>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell @click="select(row)" span="1" minWidth="5">
                            <strong><button type="button" @click="selectSchueler(row)" class="truncate">{{ row.gfs }}</button></strong>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell @click="select(row)" span="2" minWidth="5">
                            <button type="button" @click="selectSchueler(row)" class="truncate">{{ row.gfsu }}</button>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell @click="select(row)" span="2" minWidth="5">
                            <BemerkungIndicator
                            :model="row"
                            :bemerkung="row['ASV']"
                            @clicked="selectSchueler(row, 'asv')"
                        ></BemerkungIndicator>
                            <button type="button" @click="selectSchueler(row)" class="truncate">{{ row.ASV }}</button>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell @click="select(row)" span="2" minWidth="5">
                            <BemerkungIndicator
                            :model="row"
                            :bemerkung="row['AUE']"
                            @clicked="selectSchueler(row, 'aue')"
                        ></BemerkungIndicator>
                            <button type="button" @click="selectSchueler(row)" class="truncate">{{ row.AUE }}</button>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell @click="select(row)" span="2" minWidth="5">
                            <BemerkungIndicator
                            :model="row"
                            :bemerkung="row['ZB']"
                            @clicked="selectSchueler(row, 'zb')"
                        ></BemerkungIndicator>
                            <button type="button" @click="selectSchueler(row)" class="truncate">{{ row.ZB }}</button>
                        </SvwsUiDataTableCell>
                    </SvwsUiDataTableRow>
                </template>
            </SvwsUiDataTable>

            <h3 class="text-headline-sm ui-mx-6" v-else>Keine Einträge gefunden!</h3>
        </template>
    </AppLayout>
</template>

<style scoped>
.truncate {
    @apply ui-truncate
}
    header {
        @apply ui-flex ui-flex-col ui-gap-4 ui-p-6
    }

    header #headline {
        @apply ui-flex ui-items-center ui-justify-start ui-gap-6
    }

    header #filters {
        @apply ui-grid sm:ui-grid-cols-2 md:ui-grid-cols-3 lg:ui-grid-cols-6 ui-gap-6
    }
</style>
