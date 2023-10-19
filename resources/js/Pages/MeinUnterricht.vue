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
    //TODO: add functionality?
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
        SvwsUiTable,
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

    const rows: Ref<Leistung[]> = ref([])

    //TODO: check this functionality
    const selectedFbLeistung: Ref<Leistung | null> = ref(null)

    const getToggleValue = (column: string): boolean => usePage().props.value.settings.filters[column] == 1

    let toggles = <{
        teilleistungen: boolean,
        mahnungen: boolean,
        bemerkungen: boolean,
        fehlstunden: boolean
    }>reactive({
        teilleistungen: getToggleValue('mein_unterricht_teilleistungen'),
        mahnungen: getToggleValue('mein_unterricht_mahnungen'),
        bemerkungen: getToggleValue('mein_unterricht_bemerkungen'),
        fehlstunden: getToggleValue('mein_unterricht_fehlstunden'),
    })

    const auth: Auth = usePage().props.value.auth

    watch(toggles, (): void => drawTable())

    //TODO: note not working
    const columns = ref<Column[]>([])
    const klasseFilter: Ref <string[]> = ref([])
    const jahrgangFilter: Ref <string[]> = ref([])
    const fachFilter: Ref <string[]> = ref([])
    const kursFilter: Ref <string[]> = ref([])
    const noteFilter: Ref <string|number[]> = ref([])
    const searchFilter: Ref<string|null> = ref(null)
    const klasseItems: Ref<string[]> = ref([]);
    const jahrgangItems: Ref<string[]> = ref([]);
    const fachItems: Ref<string[]> = ref([]);
    const kursItems: Ref<string[]> = ref([]);
    const noteItems: Ref<string[]> = ref([]);

    let tableRedrawKey: number = 0

    //TODO: remove 'cause unnecessary to use this with uitable
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

    const getLeistungen = (): Promise<any> => axios
        .get(route('api.mein_unterricht'))
        .then((response: AxiosResponse): void => rows.value = response.data)
        .then((): string[] => klasseItems.value = mapItems("klasse"))
        .then((): string[] => jahrgangItems.value = mapItems("jahrgang"))
        .then((): string[] => fachItems.value = mapItems("fach"))
        .then((): string[] => kursItems.value = mapItems("kurs"))
        .then((): string[] => noteItems.value = mapItems("note"))

        //TODO: something's wrong with note
    const mapItems = (column: string): string[] => rows.value
        .map((leistung: Leistung): string => leistung[column])
        .filter((value: string, index:number, self: string[]): boolean => self.indexOf(value) === index)

    //TODO: shall we keep these two?
    const disabled = (condition: boolean): boolean => tableCellDisabled(condition, auth.administrator) // ok

    const select = (row: Leistung, always: boolean = false): void => {
        if (always || selectedFbLeistung.value !== null) {
            selectedFbLeistung.value = row
        }
    }

    const search = (leistung: Leistung, column: 'nachname'|'vorname'|'klasse'): boolean =>
        leistung[column].toLocaleLowerCase().includes(searchFilter.value?.toLocaleLowerCase() ?? '')

    const rowsFiltered = computed(() =>
        rows.value.filter((leistung: Leistung): boolean => {
            if (klasseFilter.value.length > 0) {
                return klasseFilter.value.includes(leistung.klasse)
            }
            if (jahrgangFilter.value.length > 0) {
                return jahrgangFilter.value.includes(leistung.jahrgang)
            }
            if (fachFilter.value.length > 0) {
                return fachFilter.value.includes(leistung.fach)
            }
            if (kursFilter.value.length > 0) {
                return kursFilter.value.includes(leistung.kurs)
            }
            if (noteFilter.value.length > 0) {
                return noteFilter.value.includes(leistung.note)
            }

            if (searchFilter.value !== null) {
                return search(leistung, 'nachname')
                    || search(leistung, 'vorname')
                    || search(leistung, 'klasse')
            }

            return true
        })
    )

    const filterReset = (): void => {
        klasseFilter.value = []
        jahrgangFilter.value = []
        fachFilter.value = []
        kursFilter.value = []
        noteFilter.value = []
        searchFilter.value = null
    }

    const filtered = (): boolean => klasseFilter.value.length > 0 ||
                                    jahrgangFilter.value.length > 0 ||
                                    fachFilter.value.length > 0 ||
                                    kursFilter.value.length > 0 ||
                                    noteFilter.value.length > 0 ||
                                    searchFilter.value !== null
    
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
            </header>


<!-- TODO: some buttons with functionality still missing (see other TODOs) -->
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
                    <template #cell(klasse)="{ value, rowData }">
                        <button
                            v-if="selectedSchueler"
                            type="button"
                            @click="selectSchueler(rowData)"
                            :aria-label="bemerkungButtonAriaLabel(rowData)"
                        >{{ value }}</button>
                        <span v-else>{{ value }}</span>
                    </template>
                    <template #cell(name)="{ value, rowData }">
                        <button
                            v-if="selectedSchueler"
                            type="button"
                            @click="selectSchueler(rowData)"
                            :aria-label="bemerkungButtonAriaLabel(rowData)"
                        >{{ value }}</button>
                        <span v-else>{{ value }}</span>
                    </template>
                    <template #cell(gfs)="{ value, rowData }">
                        <FehlstundenInput
                            column="gfs"
                            :model="rowData"
                            :disabled="fehlstundenDisabled(rowData)"
                        />
                    </template>
                    <template #cell(gfsu)="{ value, rowData }">
                        <FehlstundenInput
                            column="gfsu"
                            :model="rowData"
                            :disabled="fehlstundenDisabled(rowData)"
                        />
                    </template>
                    <template #cell(asv)="{ value, rowData }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['ASV']"
                            @clicked="selectSchueler(rowData, 'asv')"
                            :disabled="inputDisabled(rowData.matrix.editable_asv)"
                            floskelgruppe="asv"
                        />
                    </template>
                    <template #cell(aue)="{ value, rowData }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['AUE']"
                            @clicked="selectSchueler(rowData, 'aue')"
                            :disabled="inputDisabled(rowData.matrix.editable_aue)"
                            floskelgruppe="aue"
                        />
                    </template>
                    <template #cell(zb)="{ value, rowData }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['ZB']"
                            @clicked="selectSchueler(rowData, 'zb')"
                            :disabled="inputDisabled(rowData.matrix.editable_zb)"
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
    header {
        @apply ui-flex ui-flex-col ui-gap-4 ui-p-6
    }

    .content-area {
        @apply ui-mx-4
    }
</style>
