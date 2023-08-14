<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import { computed, onMounted, reactive, Ref, ref, watch, VNodeRef, provide } from 'vue'


    import { Head, usePage } from '@inertiajs/inertia-vue3'
    import { Column } from '../Interfaces/Column'
    import axios, {AxiosPromise, AxiosResponse} from 'axios'
    import { Leistung } from '../types'
    import { SortTableColumns } from '../Interfaces/SortTableColumns'
    import NoteInput from '../Components/NoteInput.vue'
    import BemerkungIndicator from '../Components/BemerkungIndicator.vue'
    import TableSortButton from '../Components/TableSortButton.vue'
    import { tableCellDisabled } from '../Helpers/pages.helper'
    import {navigateTable, Payload} from '../Helpers/tableNavigationHelper'

    type CellRef = VNodeRef | undefined

    import {
        baseColumns,
        fachbezogeneBemerkungenColumns,
        mahnungenColumns,
        fehlstundenColumns,
        fachlehrerColumns,
        notenColumns,
        teilleistungenColumns,
    } from '../Helpers/columns.helper'

    import {
        SvwsUiCheckbox,
        SvwsUiTextInput,
        SvwsUiSelectInput,
        SvwsUiIcon,
        SvwsUiDataTable,
        SvwsUiButton,
        SvwsUiTooltip,
        SvwsUiMultiSelect,
        SvwsUiDataTableCell,
        SvwsUiDataTableRow,
    } from '@svws-nrw/svws-ui'

    import MahnungIndicatorReadonly from '../Components/MahnungIndicatorReadonly.vue'
    import MahnungIndicator from '../Components/MahnungIndicator.vue'
    import FehlstundenInput from '../Components/FehlstundenInput.vue'
    import FbEditor from '../Components/FbEditor.vue'
    import {Auth} from '../Interfaces/Auth'

    const title = 'Notenmanager - Leistungsdatenübersicht'

    const getToggleValue = (column: string): boolean => usePage().props.value.settings.filters[column] == 1

    const selectedFbLeistung: Ref<Leistung | null> = ref(null)
    const selectedColumn: Ref<Leistung | null> = ref(null)

    let toggles = <{
        fachlehrer: boolean,
        bemerkungen: boolean,
        mahnungen: boolean,
        teilleistungen: boolean,
    }>reactive({
        fachlehrer: getToggleValue('leistungdatenuebersicht_teilleistungen'),
        bemerkungen: getToggleValue('leistungdatenuebersicht_fachlehrer'),
        mahnungen: getToggleValue('leistungdatenuebersicht_mahnungen'),
        teilleistungen: getToggleValue('leistungdatenuebersicht_bemerkungen'),
    })

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

    let filters = <{
        search: string,
        klasse: Number | string,
        jahrgang: Number | string,
        kurs: Number | string,
        fach: Number | string
    }>reactive({
        search: '',
        klasse: 0,
        jahrgang: 0,
        kurs: 0,
        fach: 0,
    })

    const noteFilter = ref();


    const columns = ref<Column[]>([])

    let tableRedrawKey: number = 0

    const drawTable = (): void => {
        const pushTable = (pushable: boolean, array: Array<Column>): void => {
            if (pushable) {
                array.forEach((column: Column): number => columns.value.push(column))
            }
        }

        columns.value.length = 0
        pushTable(true, baseColumns)
        pushTable(toggles.fachlehrer, fachlehrerColumns)
        pushTable(toggles.teilleistungen, teilleistungenColumns)
        pushTable(true, notenColumns)
        pushTable(toggles.mahnungen, mahnungenColumns)
        pushTable(true, fehlstundenColumns)
        pushTable(toggles.bemerkungen, fachbezogeneBemerkungenColumns)

        tableRedrawKey++;
    }

    watch(toggles, (): void => drawTable())

    onMounted((): void => {
        getLeistungen()
        drawTable()
    })


    const auth: Auth = usePage().props.value.auth as Auth

    const getFilters = (): void => {
        filterOptions.kurse = setFilters(state.leistungen, 'kurs', false)
        filterOptions.noten = setFilters(state.leistungen, 'note', true, false)
        filterOptions.jahrgaenge = setFilters(state.leistungen, 'jahrgang')
        filterOptions.klassen = setFilters(state.leistungen, 'klasse')
        filterOptions.faecher = setFilters(state.leistungen, 'fach')
    }

    const setFilters = (data, column: string, hasEmptyValue: boolean = true, hasAllValue: boolean = true): {
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

        if (hasAllValue) {
            set.unshift({ label: 'Alle', index: '0' })
        }

        return set;
    }

    const getLeistungen = (): AxiosPromise => axios
        .get(route('api.leistungsdatenuebersicht'))
        .then((response: AxiosResponse): AxiosResponse => state.leistungen = response.data)
        .finally(() => getFilters())


    const sortRef: Ref<SortTableColumns> = ref({
        direction: true,
        sortBy: 'name'
    })

    provide('sortRef', sortRef)

    const updateSortRef = (newSortRef: SortTableColumns) => {
        sortRef.value.sortBy = newSortRef.sortBy
        sortRef.value.direction =newSortRef.direction
    }


    const filteredLeistungen = computed(() => state.leistungen
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
            && tableFilter(leistung, 'klasse', true)
            && tableFilter(leistung, 'kurs', true)
            && tableFilter(leistung, 'jahrgang')
            && tableFilter(leistung, 'fach')
            && multiSelectFilter(leistung, 'note')
        )
    )

    const filteredLeistungenCount = computed(() => {
      return filteredLeistungen.value.length;
    });

    const multiSelectFilter = (leistung: Leistung, column: string): boolean => {
        const indexContains = (index: string): boolean => noteFilter.value.filter(
            (item: {index: string, label: string}): boolean => item.index === index
        ).length > 0

        if (noteFilter.value === undefined) { // No item selected
            return true
        }

        return noteFilter.value.filter(
            (item: {index: string, label: string}): boolean =>
                item.label === leistung.note
                || (indexContains('') && [null, ''].includes(leistung.note))
        ).length > 0
    }

    const searchFilter = (leistung: Leistung): boolean => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(leistung.vorname) || search(leistung.nachname)
    }

    const tableFilter = (leistung: Leistung, column: string, containsOnlyEmptyOption: boolean = false): boolean => {
        if (containsOnlyEmptyOption && [null, ''].includes(filters[column])) return leistung[column] == null
        if (filters[column] == 0) return true
        return leistung[column] == filters[column]
    }

    let lowScoreArray: Array<string> = [ // TODO: Create a helper
        '6', '5-', '5', '5+', '4-',
    ]

    const lowScore = (note: string): boolean => lowScoreArray.includes(note)


    let leistungEdit = ref(false)

    let lehrerCanOverrideFachlehrer = (usePage().props.value.settings.matrix['lehrer_can_override_fachlehrer'] == 1)

    const leistungEditToggle = () => {
        if (lehrerCanOverrideFachlehrer) {
            leistungEdit.value = !leistungEdit.value
        }
    }

    const disabled = (condition: boolean): boolean => tableCellDisabled(condition, auth.administrator, leistungEdit.value)

    const readonly = (leistung: Leistung, permission: 'editable_fb'): boolean => disabled(leistung.matrix[permission])
    const select = (row: Leistung): Leistung => selectedFbLeistung.value = row
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <AppLayout>
        <template v-slot:aside v-if="selectedFbLeistung">
            <FbEditor
                :leistung="selectedFbLeistung"
                :readonly="readonly(selectedFbLeistung, 'editable_fb')"
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
                    <SvwsUiCheckbox v-model="toggles.fachlehrer" :value="true">Fachlehrer</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.mahnungen" :value="true">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.bemerkungen" :value="true">Fachbezogene Bemerkungen</SvwsUiCheckbox>

                    <SvwsUiButton @click="leistungEditToggle()" v-if="lehrerCanOverrideFachlehrer" :type="leistungEdit ? 'primary' : 'secondary'" size="big">
                        <SvwsUiIcon>
                            <mdi-pencil></mdi-pencil>
                        </SvwsUiIcon>
                    </SvwsUiButton>
                </div>
                <div id="filters">
                    <SvwsUiTextInput type="search" placeholder="Suche" v-model="filters.search"></SvwsUiTextInput>
                    <SvwsUiSelectInput placeholder="Klasse" v-model="filters.klasse" :options="filterOptions.klassen"></SvwsUiSelectInput>
                    <SvwsUiSelectInput placeholder="Jahrgang" v-model="filters.jahrgang" :options="filterOptions.jahrgaenge"></SvwsUiSelectInput>
                    <SvwsUiSelectInput placeholder="Fach" v-model="filters.fach" :options="filterOptions.faecher"></SvwsUiSelectInput>
                    <SvwsUiSelectInput placeholder="Kurs" v-model="filters.kurs" :options="filterOptions.kurse"></SvwsUiSelectInput>
                    <SvwsUiMultiSelect
                        v-model="noteFilter"
                        title="Note"
                        :item-text="item => item?.label || ''"
                        :items="filterOptions.noten"
                        autocomplete
                        tags
                        :item-filter="(items: {index: Number, label: String}, search: String) => items.filter((i: any) => i.label.includes(search))"
                        :removable="true"
                    ></SvwsUiMultiSelect>
                </div>
            </header>
            <SvwsUiDataTable clickable :noData="false" :key="tableRedrawKey">
                <template #header>
                    <SvwsUiDataTableRow thead>
                        <SvwsUiDataTableCell thead span="1" minWidth="6">
                            <TableSortButton :presentColumn= "{sortBy:'klasse'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Klasse</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead span="3" minWidth="10">
                            <TableSortButton :presentColumn="{sortBy:'name'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Name</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead span="1" minWidth="5">
                            <TableSortButton :presentColumn="{sortBy:'fach'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Fach</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead span="2" minWidth="5">
                            <TableSortButton :presentColumn="{sortBy:'kurs'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Kurs</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead v-if="toggles.fachlehrer" span="2" minWidth="6">
                            <TableSortButton :presentColumn="{sortBy:'lehrer'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Lehrer</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead v-if="toggles.teilleistungen" span="5" minWidth="15">
                            Teilnoten
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead span="1" minWidth="5">
                            <TableSortButton :presentColumn="{sortBy:'note'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">Note</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Mahnung" v-if="toggles.mahnungen" span="1" minWidth="4">
                            M
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Fachbezogene Fehlstunden" span="1" minWidth="6">
                            <TableSortButton :presentColumn="{sortBy:'fs'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">FS</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Unentschuldigte fachbezogene Fehlstunden" span="1" minWidth="6">
                            <TableSortButton :presentColumn="{sortBy:'fsu'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">FSU</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Fachbezogene Bemerkungen" v-if="toggles.bemerkungen" span="12" minWidth="4">
                            <TableSortButton :presentColumn="{sortBy:'fachbezogeneBemerkungen'}" @clicked="(newSortRef) => { updateSortRef(newSortRef) }">FB</TableSortButton>
                        </SvwsUiDataTableCell>
                    </SvwsUiDataTableRow>
                </template>

                <template #body="{ rows }">
                    <SvwsUiDataTableRow v-for="(row, index) in filteredLeistungen" :key="index" >
                        <SvwsUiDataTableCell @click="select(row)" span="1" minWidth="6">
                            <span class="truncate">{{ row.klasse }}</span>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell @click="select(row)" span="3" minWidth="10">
                            <span class="truncate">{{ row.name }}</span>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell @click="select(row)" span="1" minWidth="5">
                            <strong class="truncate">{{ row.fach }}</strong>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell @click="select(row)" span="2" minWidth="5">
                            <span class="truncate">{{ row.kurs }}</span>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell v-if="toggles.fachlehrer" @click="select(row)" span="2" minWidth="6">
                            <span class="truncate">{{ row.lehrer }}</span>
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

                        <SvwsUiDataTableCell span="1" minWidth="6">
                            <FehlstundenInput
                                :model="row"
                                column="fs"
                                :row-index="index"
                                :disabled="disabled(row.matrix.editable_fehlstunden)"
                            />
                        </SvwsUiDataTableCell>

                        <SvwsUiDataTableCell span="1" minWidth="6">
                            <FehlstundenInput
                                :model="row"
                                column="fsu"
                                :row-index="index"
                                :disabled="disabled(row.matrix.editable_fehlstunden)"
                            />
                        </SvwsUiDataTableCell>

                        <SvwsUiDataTableCell v-if="toggles.bemerkungen" @click="select(row)" span="12" minWidth="4">
                            <BemerkungIndicator
                                :model="row"
                                :bemerkung="row.fachbezogeneBemerkungen"
                                :row-index="index"
                                :disabled="disabled(row.matrix.editable_fb)"
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
