<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import { Head } from '@inertiajs/inertia-vue3'
    import { onMounted, reactive, computed, ref, watch, PropType, Ref, provide } from 'vue'
    import { Leistung } from '../Interfaces/Leistung'
    import { SortTableColumns } from '../Interfaces/SortTableColumns'
    import TableSortButton from '../Components/TableSortButton.vue'
    import { Column } from '../Interfaces/Column'
    import { usePage } from '@inertiajs/inertia-vue3'
    import axios, { AxiosResponse } from 'axios'
    import MahnungIndicator from '../Components/MahnungIndicator.vue'
    import NoteInput from '../Components/NoteInput.vue'

    import FbEditor from '../Components/FbEditor.vue'

    import BemerkungIndicator from '../Components/BemerkungIndicator.vue'

    import {
        baseColumns,
        fachbezogeneBemerkungenColumns,
        notenColumns,
        teilleistungenColumns,
        mahnungenColumns,
        fehlstundenColumns,
    } from '../Helpers/columns.helper'

    import {
        SvwsUiCheckbox,
        SvwsUiDataTable,
        SvwsUiButton,
        SvwsUiTextInput,
        SvwsUiTooltip,
        SvwsUiMultiSelect,
        SvwsUiDataTableCell,
        SvwsUiDataTableRow,
    } from '@svws-nrw/svws-ui'

    import FehlstundenInput from '../Components/FehlstundenInput.vue'
    import {Settings} from '../Interfaces/Settings'
    import MahnungIndicatorReadonly from '../Components/MahnungIndicatorReadonly.vue'
    import {Auth} from '../Interfaces/Auth'
    import {tableCellEditable, nextNote, tableCellDisabled} from '../Helpers/pages.helper'

    const title = 'Notenmanager - mein Unterricht'

    let state = reactive({
        leistungen: <Leistung[]> [],
    })

    let filterOptions = <any>reactive({
        'jahrgaenge': [],
        'klassen': [],
        'kurse': [],
        'noten': [],
        'faecher': [],
    })

    const selectedFbLeistung: Ref<Leistung | null> = ref(null)

    //coming from 'user_settings..' now
    //const getToggleValue = (column: string): boolean => usePage().props.value.settings.filters[column] == 1

    let toggles = <{
        teilleistungen: boolean,
        mahnungen: boolean,
        bemerkungen: boolean,
        fehlstunden: boolean
    }>reactive({
    })

    axios.get(route('user_settings.get_filters', 'meinunterricht'))
        .then((response: AxiosResponse) => toggles = response.data.filters_meinunterricht)

    let props = defineProps({
        settings: {
            type: Object as PropType<Settings>,
            required: true,
        },
    })

    const auth: Auth = usePage().props.value.auth

    watch(toggles, (): void => drawTable())

    let filters = <{
        search: string,
        klasse: Number | string,
        jahrgang: Number | string,
        kurs: Number | string,
        fach: Number | string,
        note: Number | string,
    }>reactive({
        search: '',
        klasse: '0',
        jahrgang: 0,
        kurs: '0',
        fach: '0',
        note: 0,
    })

    const noteFilter = ref<string[]>([]);

    const columns = ref<Column[]>([])

    let tableRedrawKey: number = 0

    const drawTable = (): void => {
        const pushTable = (pushable: boolean, array: Array<Column>): void => {
            if (pushable) array.forEach((column: Column): number => columns.value.push(column))
        }

        columns.value.length = 0

        pushTable(true, baseColumns)
        pushTable(toggles.teilleistungen, teilleistungenColumns)
        pushTable(true, notenColumns)
        pushTable(toggles.mahnungen, mahnungenColumns)
        pushTable(toggles.fehlstunden, fehlstundenColumns)
        pushTable(toggles.bemerkungen, fachbezogeneBemerkungenColumns)

        tableRedrawKey++;
    }

    onMounted((): void => {
        getLeistungen()
        drawTable()
    })

    const getFilters = (): void => {
        filterOptions.kurse = setFilters(state.leistungen, 'kurs', false)
        filterOptions.noten = setFilters(state.leistungen, 'note')
        filterOptions.jahrgaenge = setFilters(state.leistungen, 'jahrgang')
        filterOptions.klassen = setFilters(state.leistungen, 'klasse')
        filterOptions.faecher = setFilters(state.leistungen, 'fach')
    }

    const getLeistungen = (): Promise<any> => axios
        .get(route('api.mein_unterricht'))
        .then((response: AxiosResponse): void => state.leistungen = response.data)
        .finally(() => getFilters())

    const setFilters = (data, column: string, hasEmptyValue: boolean = true): {
        label: string, index: string | null | number
    }[] => {
        let hasEmpty: boolean = true

        let set = [
            ...new Set(data.map((item: any): string => item[column]))
        ]
            .filter((item: string): boolean => {
                if (['', null].includes(item)) {
                    hasEmpty = hasEmptyValue
                    return false
                }

                return true
            })
            .map((item: string): { label: string, index: string | null | number } => {
                return { label: item, index: item }
            })

        set.sort(function(a, b) {
            let textA = a.label.toUpperCase()
            let textB = b.label.toUpperCase()
            return (textA < textB) ? -1 : (textA > textB) ? 1 : 0
        })

        if (hasEmpty) {
            set.unshift({ label: 'Leer', index: '' })
        }
        set.unshift({ label: 'Alle', index: '0' })

        return set;
    }

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

    const filteredLeistungen = computed((): Array<Leistung> => state.leistungen
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
        .filter((leistung: Leistung): boolean =>
            searchFilter(leistung)
            && tableFilter(leistung, 'kurs')
            && tableFilter(leistung, 'klasse')
            && tableFilter(leistung, 'jahrgang')
            && tableFilter(leistung, 'fach')
            && multiSelectFilter(leistung, 'note')
        )
    )

    const multiSelectFilter = (leistung: Leistung, column: string): boolean => {
        const indexContains = (index: string): boolean => noteFilter.value.filter(
            (item: {index: string, label: string}): boolean => item.index === index
        ).length > 0

        const displayAll = (): boolean => {
            let optionAllSelected: boolean = false
            if (noteFilter.value.length > 0) { // at least one item selected
                noteFilter.value.forEach((item) => {
                    if (item.index === '0') {
                        optionAllSelected = true
                        return
                    }
                })
            }
            return optionAllSelected
        }

        if (noteFilter.value.length === 0) { // No item selected or everything just unselected
            return true
        } 
        
        return noteFilter.value.filter(
            (item: {index: string, label: string}): boolean =>
                item.label === leistung.note
                || (indexContains('') && [null, ''].includes(leistung.note))
                || displayAll()
        ).length > 0
    }

    const searchFilter = (leistung: Leistung): boolean => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(leistung.vorname) || search(leistung.nachname)
    }

    const tableFilter = (leistung: Leistung, column: string): boolean => {
        if ((filters[column] == 0 || filters[column]['index'] == 0) && filters[column]['index'] !== '') return true
        if (filters[column]['index'] === "") return (leistung[column] == null)
        return leistung[column] == filters[column]['index']
    }

    const disabled = (condition: boolean): boolean => tableCellDisabled(condition, auth.administrator) // ok

    const select = (row: Leistung, always: boolean = false): void => {
        if (always || selectedFbLeistung.value !== null) {
            selectedFbLeistung.value = row
        }
    }
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>
    <AppLayout>
        <template v-slot:aside v-if="selectedFbLeistung">
            <FbEditor
                :leistung="selectedFbLeistung"
                :readonly="!selectedFbLeistung.matrix.editable_fb"
                @close="selectedFbLeistung = null"
                @updated="selectedFbLeistung.fachbezogeneBemerkungen = $event; drawTable()"
            ></FbEditor>
        </template>

        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">{{ title }}</h2>
                </div>
                <div id="toggles">
                    <SvwsUiCheckbox v-model="toggles.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.mahnungen" :value="true">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.fehlstunden" :value="true">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.bemerkungen" :value="true">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                </div>
                <div id="filters">
                    <SvwsUiTextInput type="search" placeholder="Suche" v-model="filters.search"></SvwsUiTextInput>
                    <SvwsUiMultiSelect
                        title="Klasse"
                        v-model="filters.klasse"
                        :items="filterOptions.klassen"
                        :item-text="item => item?.label || ''"
                        autocomplete
                        :removable="false"
                    ></SvwsUiMultiSelect>
                    <SvwsUiMultiSelect
                        title="Jahrgang"
                        v-model="filters.jahrgang"
                        :items="filterOptions.jahrgaenge"
                        :item-text="item => item?.label || ''"
                        autocomplete
                        :removable="false"
                    ></SvwsUiMultiSelect>
                    <SvwsUiMultiSelect
                        title="Fach"
                        v-model="filters.fach"
                        :items="filterOptions.faecher"
                        :item-text="item => item?.label || ''"
                        autocomplete
                        :removable="false"
                    ></SvwsUiMultiSelect>
                    <SvwsUiMultiSelect
                        title="Kurs"
                        v-model="filters.kurs"
                        :items="filterOptions.kurse"
                        :item-text="item => item?.label || ''"
                        autocomplete
                        :removable="false"
                    ></SvwsUiMultiSelect>
                    <SvwsUiMultiSelect
                        title="Note"
                        v-model="noteFilter"
                        :item-text="item => item?.label || ''"
                        :items="filterOptions.noten"
                        autocomplete
                        tags
                        :item-filter="(items: {index: Number, label: String}, search: String) => items.filter((i: any) => i.label.includes(search))"
                        :removable="false"
                    ></SvwsUiMultiSelect>
                </div>
            </header>

            <h3 class="text-headline-sm ui-mx-6" v-if="filteredLeistungen.length === 0">Keine Einträge gefunden!</h3>
            <SvwsUiDataTable v-else clickable :noData="false" :key="tableRedrawKey">
                <!-- TODO: was istGemahn relevant? <template #header(istGemahnt)="{ column: { label } }"> -->
                <template #header>
                    <SvwsUiDataTableRow thead>
                        <SvwsUiDataTableCell thead span="1" minWidth="6">
                            <TableSortButton :presentColumn="{ sortBy: 'klasse' }" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Klasse</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead span="3" minWidth="10">
                            <TableSortButton :presentColumn="{ sortBy: 'name' }" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Name</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead span="1" minWidth="5">
                            <TableSortButton :presentColumn="{ sortBy: 'fach' }" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Fach</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead span="2" minWidth="5">
                            <TableSortButton :presentColumn="{ sortBy: 'kurs' }" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Kurs</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead v-if="toggles.teilleistungen" span="5" minWidth="15">
                            Teilnoten
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead span="1" minWidth="5">
                            <TableSortButton :presentColumn="{ sortBy: 'note' }" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Note</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Mahnung" v-if="toggles.mahnungen" span="1" minWidth="4">
                            M
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Fachbezogene Fehlstunden" v-if="toggles.fehlstunden" span="1" minWidth="6">
                            <TableSortButton :presentColumn="{ sortBy: 'fs' }" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">FS</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Unentschuldigte fachbezogene Fehlstunden" v-if="toggles.fehlstunden" span="1" minWidth="6">
                            <TableSortButton :presentColumn="{ sortBy: 'fsu' }" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">FSU</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Fachbezogene Bemerkungen" v-if="toggles.bemerkungen" span="12" minWidth="4">
                            <TableSortButton :presentColumn="{ sortBy: 'fachbezogeneBemerkungen' }" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">FB</TableSortButton>
                        </SvwsUiDataTableCell>
                    </SvwsUiDataTableRow>
                </template>
                <template #body="{ rows }">
                    <SvwsUiDataTableRow v-for="(row, index) in filteredLeistungen" :key="index" >
                        <SvwsUiDataTableCell span="1" minWidth="6">
                            <button type="button" @click="select(row)" class="truncate">
                                {{ row.klasse }}
                            </button>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell span="3" minWidth="10">
                            <button type="button" @click="select(row)" class="truncate">
                                {{ row.name }}
                            </button>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell span="1" minWidth="5">
                            <button type="button" @click="select(row)" class="truncate font-bold">
                                {{ row.fach }}
                            </button>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell span="2" minWidth="5">
                            <button type="button" @click="select(row)" class="truncate">
                                {{ row.kurs }}
                            </button>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell v-if="toggles.teilleistungen" span="5" minWidth="15">
                            <span class="truncate">TBD</span>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell span="1" minWidth="5">
                            <NoteInput
                                :leistung="row"
                                :row-index="index"
                                :disabled="disabled(row.matrix.editable_noten)"
                            ></NoteInput>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell v-if="toggles.mahnungen" span="1" minWidth="4">
                            <MahnungIndicator
                                :leistung="row"
                                :row-index="index"
                                :disabled="disabled(row.matrix.editable_mahnungen)"
                            ></MahnungIndicator>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell v-if="toggles.fehlstunden" span="1" minWidth="6">
                            <FehlstundenInput
                                :model="row"
                                column="fs"
                                :row-index="index"
                                :disabled="disabled(row.matrix.editable_fehlstunden && row.matrix.toggleable_fehlstunden)"
                            />
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell v-if="toggles.fehlstunden" span="1" minWidth="6">
                            <FehlstundenInput
                                :model="row"
                                column="fsu"
                                :row-index="index"
                                :disabled="disabled(row.matrix.editable_fehlstunden && row.matrix.toggleable_fehlstunden)"
                            />
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell v-if="toggles.bemerkungen" span="12" minWidth="4">
                            <BemerkungIndicator
                                :model="row"
                                :bemerkung="row.fachbezogeneBemerkungen"
                                :row-index="index"
                                @click="select(row, true)"
                            ></BemerkungIndicator>
                        </SvwsUiDataTableCell>
                    </SvwsUiDataTableRow>
                </template>
            </SvwsUiDataTable>
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

    header #toggles {
        @apply ui-flex ui-items-center ui-justify-start ui-gap-3 ui-flex-wrap
    }

    header #headline {
        @apply ui-flex ui-items-center ui-justify-start ui-gap-6
    }

    header #filters {
        @apply ui-grid sm:ui-grid-cols-2 md:ui-grid-cols-3 lg:ui-grid-cols-6 ui-gap-6
    }
</style>
