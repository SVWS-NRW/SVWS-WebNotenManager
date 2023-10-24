<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import { computed, onMounted, reactive, Ref, ref, watch, VNodeRef, provide } from 'vue'


    import { Head, usePage } from '@inertiajs/inertia-vue3'
    import { Column } from '../Interfaces/Column'
    import axios, {AxiosPromise, AxiosResponse} from 'axios'
    import { Leistung } from '../types'
    import { SortTableColumns } from '../Interfaces/SortTableColumns'
    //TODO: add functionalities after refactoring uitable
    import NoteInput from '../Components/NoteInput.vue'
    import BemerkungIndicator from '../Components/BemerkungIndicator.vue'
    //unused after npm7?
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
        SvwsUiTable,
        //deprecated
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

    const rows: Ref<Leistung[]> = ref([])

    const getToggleValue = (column: string): boolean => usePage().props.value.settings.filters[column] == 1

    //TODO: check this functionality
    const selectedFbLeistung: Ref<Leistung | null> = ref(null)

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

    const klasseFilter: Ref <string[]> = ref([])
    const jahrgangFilter: Ref <string[]> = ref([])
    const fachFilter: Ref <string[]> = ref([])
    const kursFilter: Ref <string[]> = ref([])
    const noteFilter: Ref<string[]> = ref([])
    const searchFilter: Ref<string|null> = ref(null)
    const noteItems: Ref<string[]> = ref([])
    const klasseItems: Ref<string[]> = ref([])
    const jahrgangItems: Ref<string[]> = ref([])
    const fachItems: Ref<string[]> = ref([])
    const kursItems: Ref<string[]> = ref([])

    const auth: Auth = usePage().props.value.auth as Auth

    const getLeistungen = (): Promise<any> => axios
        .get(route('api.leistungsdatenuebersicht'))
        .then((response: AxiosResponse): void => rows.value = response.data)
        .then((): string[] => klasseItems.value = mapItems("klasse"))
        .then((): string[] => jahrgangItems.value = mapItems("jahrgang"))
        .then((): string[] => fachItems.value = mapItems("fach"))
        .then((): string[] => kursItems.value = mapItems("kurs"))
        .then((): string[] => noteItems.value = mapItems("note"))

    //TODO: should empty be an option too? if so, null should be transformed
    const mapItems = (column: string): string[] => rows.value
        .map((leistung: Leistung): string => leistung[column])
        .filter((value: string, index:number, self: string[]): boolean => self.indexOf(value) === index && value !== null)

    onMounted((): void => {
        getLeistungen()
        drawTable()
    })

    const searchInput = (leistung: Leistung): boolean => {
        const search = (search: string) => search.toLocaleLowerCase().includes(searchFilter.value?.toLocaleLowerCase() ?? '')
        return search(leistung.nachname)
            || search(leistung.vorname)
            || search(leistung.klasse)
    }

    const multiSelectFilter = (leistung: Leistung, search: string): boolean => {
        switch (search) {
            case "klasse":
                if (klasseFilter.value.length > 0) {
                    return klasseFilter.value.includes(leistung.klasse)
                }
            case "jahrgang":
                if (jahrgangFilter.value.length > 0) {
                    return jahrgangFilter.value.includes(leistung.jahrgang)
                }
            case "fach":
                if (fachFilter.value.length > 0) {
                    return fachFilter.value.includes(leistung.fach)
                }
            case "kurs":
                if (kursFilter.value.length > 0) {
                    return kursFilter.value.includes(leistung.kurs)
                }
            case "note":
                if (noteFilter.value.length > 0) {
                    return noteFilter.value.includes(leistung.note)
                }
            default:
                return true
        }
    }

    const rowsFiltered = computed(() =>
        rows.value.filter((leistung: Leistung): boolean =>
            searchInput(leistung)
            && multiSelectFilter(leistung, "klasse")
            && multiSelectFilter(leistung, "jahrgang")
            && multiSelectFilter(leistung, "fach")
            && multiSelectFilter(leistung, "kurs")
            && multiSelectFilter(leistung, "note")
        )
    )

    const filterReset = (): void => {
        klasseFilter.value = []
        jahrgangFilter.value = []
        fachFilter.value = []
        kursFilter.value = []
        noteFilter.value = []
        searchFilter.value = ""
    }

    const filtered = (): boolean => klasseFilter.value.length > 0 ||
                                    jahrgangFilter.value.length > 0 ||
                                    fachFilter.value.length > 0 ||
                                    kursFilter.value.length > 0 ||
                                    noteFilter.value.length > 0 ||
                                    searchFilter.value !== null


    let lowScoreArray: Array<string> = [ // TODO: Create a helper
        '6', '5-', '5', '5+', '4-',
    ]

    const lowScore = (note: string): boolean => lowScoreArray.includes(note)

//TODO: check  all the following after refactoring to uitable
    let leistungEdit = ref(false)

    let lehrerCanOverrideFachlehrer = (usePage().props.value.settings.matrix['lehrer_can_override_fachlehrer'] == 1)

    const leistungEditToggle = () => {
        if (lehrerCanOverrideFachlehrer) {
            leistungEdit.value = !leistungEdit.value
        }
    }

    const inputDisabled = (condition: boolean): boolean => tableCellDisabled(condition, auth.administrator)

    //TODO: check if working as expected
    const disabled = (condition: boolean): boolean => tableCellDisabled(condition, auth.administrator, leistungEdit.value)

    const readonly = (leistung: Leistung, permission: 'editable_fb'): boolean => disabled(leistung.matrix[permission])
    const select = (row: Leistung, always: boolean = false): void => {
        if (always || selectedFbLeistung.value !== null) {
            selectedFbLeistung.value = row
        }
    }

    const fehlstundenDisabled = (rowData: any): boolean =>
        rowData.matrix.editable_fehlstunden && !rowData.matrix.toggleable_fehlstunden

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
                        <mdi-pencil></mdi-pencil>
                    </SvwsUiButton>
                </div>
            </header>
            <!-- TODO: keep :noData="false":key="tableRedrawKey"? -->
            <div class="content-area">
                <SvwsUiTable
                    :items="rowsFiltered.values()"
                    :columns="columns"
                    :clickable="true"
                    :count="true"
                    :filtered="filtered()"
                    :filterReset="filterReset"
                >
                    <template #filterAdvanced>
                        <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter"></SvwsUiTextInput>
                        <SvwsUiMultiSelect
                            label="Klasse"
                            v-model="klasseFilter"
                            :items="klasseItems"
                            :item-text="item => item"
                        ></SvwsUiMultiSelect>
                            <SvwsUiMultiSelect
                            label="Jahrgang"
                            v-model="jahrgangFilter"
                            :items="jahrgangItems"
                            :item-text="item => item"
                        ></SvwsUiMultiSelect>
                        <SvwsUiMultiSelect
                            label="Fach"
                            v-model="fachFilter"
                            :items="fachItems"
                            :item-text="item => item"
                        ></SvwsUiMultiSelect>
                        <SvwsUiMultiSelect
                            label="Kurs"
                            v-model="kursFilter"
                            :items="kursItems"
                            :item-text="item => item"
                        ></SvwsUiMultiSelect>
                        <SvwsUiMultiSelect
                            label="Note"
                            v-model="noteFilter"
                            :items="noteItems"
                            :item-text="item => item"
                            ></SvwsUiMultiSelect>
                    </template>
<!-- TODO: check functionalities here -->
                    <template #cell(klasse)="{ value, rowData }">
                        <button type="button" @click="select(rowData)" class="truncate">
                                {{ value }}
                        </button>
                    </template>
                    <template #cell(name)="{ value, rowData }">
                        <button type="button" @click="select(rowData)" class="truncate">
                                {{ value }}
                        </button>
                    </template>
                    <template #cell(fach)="{ value, rowData }">
                        <button type="button" @click="select(rowData)" class="truncate">
                                {{ value }}
                        </button>
                    </template>
                    <template #cell(kurs)="{ value, rowData }">
                        <button type="button" @click="select(rowData)" class="truncate">
                                {{ value }}
                        </button>
                    </template>
                    <template #cell(lehrer)="{ value, rowData }">
                            <button @click="select(rowData)" class="truncate">
                                {{ value }}
                            </button>
                    </template>
                    <template #cell(teilnoten)>
                        <button type="button" class="truncate">
                            TBD
                        </button>
                    </template>
                    <!-- TODO: keyboard navigation not working properly, index is undefined, right? get it somewhere, dummy for now -->
                    <template #cell(note)="{ value, rowData, rowIndex}">
                        <NoteInput
                                :leistung="rowData"
                                :row-index="rowIndex"
                                :disabled="disabled(rowData.matrix.editable_noten)"
                        ></NoteInput>
                    </template>
                    <!-- TODO: correct because it is breaking the page right now -->
                        <!-- <template #cell(istGemahnt)="{ value, rowData, rowIndex}">
                            <MahnungIndicator
                                    :leistung="rowData"
                                    :row-index="rowIndex"
                                    :disabled="disabled(rowData.matrix.editable_mahnungen)"
                            ></MahnungIndicator>
                        </template> -->
                    <template #cell(fs)="{ value, rowData, rowIndex }">
                        <FehlstundenInput
                            :model="rowData"
                            :row-index="rowIndex"
                            column="fs"
                            :disabled="fehlstundenDisabled(rowData)"
                        />
                    </template>
                    <template #cell(fsu)="{ value, rowData, rowIndex  }">
                        <FehlstundenInput
                            :model="rowData"
                            :row-index="rowIndex"
                            column="fsu"
                            :disabled="fehlstundenDisabled(rowData)"
                        />
                    </template>
                    <template #cell(fachbezogeneBemerkungen)="{ value, rowData }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData.fachbezogeneBemerkungen"
                            @clicked="select(rowData, 'FB')"
                            :disabled="inputDisabled(rowData.matrix.editable_fb)"
                            floskelgruppe="zb"
                        />
                    </template>
                </SvwsUiTable>
            </div>
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

    .content-area {
        @apply ui-mx-4
    }
</style>
