<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import {computed, onMounted, reactive, Ref, ref, watch, provide} from 'vue'

    import { Head, usePage } from '@inertiajs/inertia-vue3'
    import { Column } from '../Interfaces/Column'
    import axios, {AxiosPromise, AxiosResponse} from 'axios'
    import { Leistung } from '../Interfaces/Leistung'
    import { SortTableColumns } from '../Interfaces/SortTableColumns'
    import NoteInput from '../Components/NoteInput.vue'
    import BemerkungIndicator from '../Components/BemerkungIndicator.vue'
    import TableSortButton from '../Components/TableSortButton.vue'
    import { tableCellEditable, tableCellDisabled } from '../Helpers/pages.helper'

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
        .then((response: AxiosResponse): AxiosResponse => state.leistungen = mapLeistungen(response.data))
        .finally(() => getFilters())

    const mapLeistungen = (data) => data.map((leistung: Leistung): Leistung => {
        leistung.name = `${leistung.nachname}, ${leistung.vorname}`
        return leistung
    })

    // const direction = ref(true);
    // const sortBy: Ref<SortTableColumns> = ref('name');

    const sortRef: Ref<SortTableColumns> = ref({
        direction: true,
        sortBy: 'name'
    })

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
    const test = () => alert(123)

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
            <SvwsUiDataTable clickable :noData="false">
                <template #header>
                    <SvwsUiDataTableRow thead>
            <!-- TODO: use provide/inject instead of specifiying it here everytime-->
            <!-- TODO: use event for return values-->
                        <SvwsUiDataTableCell thead>
                            <TableSortButton :sortRef="sortRef" :name= "{direction:true, sortBy:'klasse'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">Klasse</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead>
                            <TableSortButton :sortRef="sortRef" :name="{direction:true, sortBy:'name'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">Name</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead>
                            <TableSortButton :sortRef="sortRef" :name="{direction:true, sortBy:'fach'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">Fach</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead>
                            <TableSortButton :sortRef="sortRef" :name="{direction:true, sortBy:'kurs'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">Kurs</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead v-if="toggles.fachlehrer">
                            <TableSortButton :sortRef="sortRef" :name="{direction:true, sortBy:'lehrer'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">Lehrer</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead v-if="toggles.teilleistungen">
                            Teilnoten
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead>
                            <TableSortButton :sortRef="sortRef" :name="{direction:true, sortBy:'note'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">Note</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead v-if="toggles.mahnungen">
                            <TableSortButton :sortRef="sortRef" :name="{direction:true, sortBy:'mahnung'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">Mahnung</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Fachbezogene Fehlstunden">
                            <TableSortButton :sortRef="sortRef" :name="{direction:true, sortBy:'fs'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">FS</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Unentschuldigte fachbezogene Fehlstunden">
                            <TableSortButton :sortRef="sortRef" :name="{direction:true, sortBy:'fsu'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">FSU</TableSortButton>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell thead tooltip="Fachbezogene Bemerkungen" v-if="toggles.bemerkungen">
                            <TableSortButton :sortRef="sortRef" :name="{direction:true, sortBy:'fachbezogeneBemerkungen'}" @clicked="(newSortRef) => { sortRef.sortBy = newSortRef.sortBy, sortRef.direction = newSortRef.newDirection }">Klasse</TableSortButton>
                        </SvwsUiDataTableCell>
                    </SvwsUiDataTableRow>
                </template>

                <template #body="{ rows }" >
                    <SvwsUiDataTableRow v-for="(row, index) in filteredLeistungen" :key="index">
                        <SvwsUiDataTableCell disabled @click="select(row)">
                            <span class="truncate">{{ row.klasse }}</span>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell disabled @click="select(row)">
                            <span class="truncate">{{ row.name }}</span>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell disabled @click="select(row)">
                            <strong class="truncate">{{ row.fach }}</strong>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell disabled @click="select(row)">
                            <span class="truncate">{{ row.kurs }}</span>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell disabled v-if="toggles.fachlehrer" @click="select(row)">
                            <span class="truncate">{{ row.lehrer }}</span>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell :disabled="disabled(row.matrix.editable_teilnoten)" v-if="toggles.teilleistungen">
                            <span class="truncate">TBD</span>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell :disabled="disabled(row.matrix.editable_noten)">
                            <NoteInput :leistung="row" :key="row.id" v-if="!disabled(row.matrix.editable_noten)"></NoteInput>
                            <strong :class="{ 'low-score' : lowScore(row.note) }" v-else>
                                {{ row.note }}
                            </strong>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell :disabled="disabled(row.matrix.editable_mahnungen)" v-if="toggles.mahnungen">
                            <MahnungIndicator :leistung="row" :key="row.id" :disabled="false" v-if="!disabled(row.matrix.editable_mahnungen)"></MahnungIndicator>
                            <MahnungIndicatorReadonly :leistung="row" :disabled="true" v-else></MahnungIndicatorReadonly>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell :disabled="disabled(row.matrix.editable_fehlstunden && row.matrix.toggleable_fehlstunden)">
                            <FehlstundenInput :model="row" column="fs" v-if="!disabled(row.matrix.editable_fehlstunden && row.matrix.toggleable_fehlstunden)"></FehlstundenInput>
                            <strong v-else>{{ row.fs }}</strong>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell :disabled="disabled(row.matrix.editable_fehlstunden && row.matrix.toggleable_fehlstunden)">
                            <FehlstundenInput :model="row" column="fsu" v-if="!disabled(row.matrix.editable_fehlstunden && row.matrix.toggleable_fehlstunden)"></FehlstundenInput>
                            <strong v-else>{{ row.fsu }}</strong>
                        </SvwsUiDataTableCell>
                        <SvwsUiDataTableCell :disabled="disabled(row.matrix.editable_fb)" @click="select(row)" v-if="toggles.bemerkungen">
                             <BemerkungIndicator :model="row" :bemerkung="row.fachbezogeneBemerkungen"></BemerkungIndicator>
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

/*.editable {*/
/*    @apply ui-bg-grey ui-w-full ui-block ui-h-full*/
/*}*/

/*.header {*/
/*    @apply ui-flex ui-flex-col ui-gap-4 ui-p-6*/
/*}*/

/*.header__headline {*/
/*    @apply ui-flex ui-gap-6 ui-justify-between*/
/*}*/

/*.header__headline__left {*/
/*    @apply ui-flex ui-flex-col ui-gap-4*/
/*}*/

/*.header__toggles {*/
/*    @apply ui-flex ui-items-center ui-justify-start ui-gap-3 ui-flex-wrap*/
/*}*/


/*header #headline {*/
/*    @apply ui-flex ui-items-center ui-justify-start ui-gap-6*/
/*}*/

/*header #filters {*/
/*    @apply ui-grid sm:ui-grid-cols-2 md:ui-grid-cols-3 lg:ui-grid-cols-6 ui-gap-6*/
/*}*/

/*header #header {*/
/*    @apply ui-flex ui-gap-6 ui-justify-between*/
/*}*/


/* .low-score {*/
/*     @apply ui-text-red-500 ui-font-bold*/
/* }*/

/* .input-override {*/
/*     @apply ui-flex ui-gap-6 ui-justify-between ui-items-center ui-w-full*/
/* }*/

/* .input-override svg {*/
/*     @apply ui-text-red-600*/
/* }*/
</style>
