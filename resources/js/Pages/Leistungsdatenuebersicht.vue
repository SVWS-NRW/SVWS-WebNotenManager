<template>
    <Head>
        <title>Notenmanager - Leistungsdatenübersicht</title>
    </Head>

    <AppLayout>
         <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="content-area">
                <SvwsUiTable :items="rowsFiltered" :columns="cols" clickable count noDataText="" :toggle-columns="true" :filtered="isFiltered()" :filterReset="filterReset"
                    :filterOpen="false" :sortByAndOrder="{ key: 'klasse', order: true}" :hiddenColumns="hiddenColumns" :allowArrowKeySelection="true">
                    <template #filter>
                        <div class="filter-area-icon">
                            <SvwsUiButton @click="leistungEditableToggle()" v-if="lehrerCanOverrideFachlehrer || props.auth.administrator"
                                :class="'hover:opacity-100 focus-visible:opacity-100'"
                                :type="leistungEditable ? 'primary' : 'transparent'" size="big">
                                <ri-pencil-fill></ri-pencil-fill>edit
                            </SvwsUiButton>
                        </div>
                        <div class="filter-area-icon">
                            <SvwsUiButton @click="exportToFile()" type="transparent" size="big"
                                :class="'hover:opacity-100 focus-visible:opacity-100 export-button'">
                                <ri-download-2-line></ri-download-2-line>csv
                            </SvwsUiButton>
                        </div>
                    </template>
                    <template #filterAdvanced>
                        <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
                        <SvwsUiMultiSelect label="Klasse" :items="klasseItems" :item-text="(item: any) => item" v-model="klasseFilter" />
                        <SvwsUiMultiSelect label="Jahrgang" :items="jahrgangItems" :item-text="(item: any) => item" v-model="jahrgangFilter" />
                        <SvwsUiMultiSelect label="Fach" :items="fachItems" :item-text="(item: any) => item" v-model="fachFilter" />
                        <SvwsUiMultiSelect label="Kurs" :items="kursItems" :item-text="(item: any) => item" v-model="kursFilter" />
                        <SvwsUiMultiSelect label="Note" :items="noteItems" :item-text="(item: any) => item" v-model="noteFilter" />
                    </template>

                    <template #cell(klasse)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <template #cell(name)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <template #cell(fach)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <template #cell(kurs)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <template #cell(lehrer)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <template #cell(note)="{ value, rowData, rowIndex }">
                        <NoteInput column="note" :leistung="rowData" :disabled="inputDisabled(rowData.editable.noten)" :row-index="rowIndex" @navigated="navigateTable" @updatedNavigableItems="updateNavigableItems" />
                    </template>

                    <template #cell(quartalnoten)="{ value, rowData, rowIndex }">
                        <NoteInput column="quartalnote" :leistung="rowData" :disabled="inputDisabled(rowData.editable.noten)" :row-index="rowIndex" @navigated="navigateTable" @updatedNavigableItems="updateNavigableItems" />
                    </template>

                    <template #cell(istGemahnt)="{ value, rowData, rowIndex }">
                        <MahnungIndicator :leistung="rowData" :disabled="inputDisabled(rowData.editable.mahnungen)" :row-index="rowIndex" @navigated="navigateTable" @updatedNavigableItems="updateNavigableItems" />
                    </template>

                    <template #cell(fs)="{ value, rowData, rowIndex }">
                        <FehlstundenInput column="fs" :model="rowData" :disabled="inputDisabled(rowData.editable.fehlstunden)" :row-index="rowIndex" @navigated="navigateTable" @updatedNavigableItems="updateNavigableItems" />
                    </template>

                    <template #cell(fsu)="{ value, rowData, rowIndex }">
                        <FehlstundenInput column="fsu" :model="rowData" :disabled="inputDisabled(rowData.editable.fehlstunden)" :row-index="rowIndex" @navigated="navigateTable" @updatedNavigableItems="updateNavigableItems" />
                    </template>

                    <template #cell(fachbezogeneBemerkungen)="{ value, rowData }">
                        <BemerkungIndicator :model="rowData" :bemerkung="rowData.fachbezogeneBemerkungen" @clicked="selectLeistung(rowData, true)" floskelgruppe="fb" />
                    </template>
                </SvwsUiTable>
            </div>
        </template>
        <template v-slot:aside v-if="selectedLeistung">
            <FbEditor :leistung="selectedLeistung" @updated="selectedLeistung.fachbezogeneBemerkungen = $event;" @close="selectedLeistung = null"
                :editable="leistungEditable"></FbEditor>
        </template>
    </AppLayout>
</template>


<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue';
    import { Head } from '@inertiajs/inertia-vue3';
    import { computed, onMounted, ref, Ref, toRaw } from 'vue';
    import axios, { AxiosResponse } from 'axios';
    import { Leistung, TableColumnToggle } from '@/Interfaces/Interface';
    import { mapFilterOptionsHelper, multiSelectHelper, searchHelper } from '@/Helpers/tableHelper';
    import { SvwsUiHeader, DataTableColumn, SvwsUiTable, SvwsUiTextInput, SvwsUiMultiSelect, SvwsUiButton, } from '@svws-nrw/svws-ui';
    import { BemerkungButton, BemerkungIndicator, FbEditor, FehlstundenInput, MahnungIndicator, NoteInput, } from '@/Components/Components';
    import { updateNavigableItems, navigateTable } from '@/Helpers/tableNavigationHelper';
    import { exportDataToCSV } from '@/Helpers/exportHelper';
    import { Auth } from '@/Interfaces/Interface';

    let props = defineProps<{
        auth: Auth
    }>();

    //Correlation filter names and column names on this page
    interface LeistungsdatenuebersichtFiltersToCols {
        [index: string]: string,
        fach: string,
        kurs: string,
        fachlehrer: string,
        //hidden here according to ticket 386
        //teilleistungen: string,
        quartalnoten: string,
        note: string,
        mahnungen: string,
        fehlstunden: string,
        bemerkungen: string,
    };

    const title = 'Notenmanager - Leistungsdatenübersicht';

    //rows will receive a reference map which will allow navigation within the three input columns of MeinUnterricht
    const itemRefsNoteInput = ref(new Map());
    const itemRefsQuartalNoteInput = ref(new Map());
    const itemRefsfs = ref(new Map());
    const itemRefsfsu = ref(new Map());
    //testing here for ticket 341
    const mahnungIndicator = ref(new Map());

    const rows: Ref<Leistung[]> = ref([]);

    //in case some delimitation takes place under #filterAdvanced, data are filtered before display in the table
    const rowsFiltered = computed((): Leistung[] => {
        return rows.value.filter((leistung: Leistung): boolean => {
            return searchHelper(leistung, ['name'], searchFilter.value || '')
                && multiSelectHelper(leistung, 'klasse', klasseFilter.value)
                && multiSelectHelper(leistung, 'fach', fachFilter.value)
                && multiSelectHelper(leistung, 'kurs', kursFilter.value)
                && multiSelectHelper(leistung, 'jahrgang', jahrgangFilter.value)
                && multiSelectHelper(leistung, 'note', noteFilter.value)
        });
    });

    //the pencil icon on top of the page is displayed and clickable only the user has the right to do so
    let lehrerCanOverrideFachlehrer = ref(false);
    let leistungEditable: Ref<boolean> = ref(false);

    const leistungEditableToggle = (): void => {
        if (true === lehrerCanOverrideFachlehrer.value || props.auth.administrator) {
            leistungEditable.value = !leistungEditable.value;
        }
    };

    //these columns can be hidden/displayed on the page, which can overwrite the platform general settings under Einstellungen/Filter
    const toggles: Ref<TableColumnToggle> = ref({
        //teilleistungen: false,
        quartalnoten: false,
        fachlehrer: false,
        mahnungen: false,
        fehlstunden: false,
        bemerkungen: false,
        kurs: false,
        note: false,
        fach: false,
    });

    const searchFilter: Ref<string|null> = ref(null);
    const klasseFilter: Ref<string[]> = ref([]);
    const fachFilter: Ref<string[]> = ref([]);
    const kursFilter: Ref<string[]> = ref([]);
    const jahrgangFilter: Ref<string[]> = ref([]);
    const noteFilter: Ref<string[]> = ref([]);

    const klasseItems: Ref<string[]> = ref([]);
    const fachItems: Ref<string[]> = ref([]);
    const kursItems: Ref<string[]> = ref([]);
    const jahrgangItems: Ref<string[]> = ref([]);
    const noteItems: Ref<string[]> = ref([]);
    const allNotes: Ref<string[]> = ref([]);


    const inputDisabled = (condition: boolean): boolean => !(condition && leistungEditable.value );

    onMounted((): Promise<void> => axios
        .get(route('api.leistungsdatenuebersicht'))
        .then((response: AxiosResponse): void => {
            rows.value = response.data.data;
            toggles.value = response.data.toggles;
            getHiddenColumns(toggles);
            lehrerCanOverrideFachlehrer.value = response.data.lehrerCanOverrideFachlehrer;
            allNotes.value = response.data.allNotes;
        })
        .finally((): void => mapFilters())
    );

    // columns used for sorting the data
    const default_cols : DataTableColumn[] = [
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, fixedWidth: 6, disabled: false, toggleInvisible:true },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 16, disabled: false, toggleInvisible:true },
    ];

    // the other columns received from DB
    const cols = computed((): DataTableColumn[] => {
        const result = [...default_cols];
        result.push({ key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, disabled: false, toggle: true });
        result.push({ key: 'kurs', label: 'Kurs', sortable: true, span: 2, minWidth: 5, disabled: false, toggle: true });
        result.push({ key: 'lehrer', label: 'Fachlehrer', sortable: true, span: 2, minWidth: 7, toggle: true });
        //Teilleistungen column hidden here for the time being (ticket 386)
        //result.push({ key: 'teilnoten', label: 'Teilnoten', sortable: true, span: 5, minWidth: 6, toggle: true });
        result.push({ key: 'quartalnoten', label: 'Quartal', sortable: true, span: 1, minWidth: 6, toggle: true });
        result.push({ key: 'note', label: 'Note', sortable: true, span: 1, minWidth: 6, toggle: true });
        result.push({ key: 'istGemahnt', label: 'Mahnungen', sortable: true, span: 1, minWidth: 8, toggle: true });
        result.push({ key: 'fs', label: 'FS', sortable: true, span: 1, minWidth: 6, tooltip: "Fachbezogene Fehlstunden", toggle: true });
        result.push({ key: 'fsu', label: 'FSU', sortable: true, span: 1, minWidth: 6, tooltip: "Unentschuldigte fachbezogene Fehlstunden", toggle: true });
        result.push({ key: 'fachbezogeneBemerkungen', label: 'FB', sortable: true, span: 12, minWidth: 4, tooltip: "Fachbezogene Bemerkungen", toggle: true });
        return result;
    });

    //filters from settings or user settings determine whether columns are hidden or shown in the table
    const hiddenColumns = ref<Set<string>>(new Set<string>());
    const filtersToCols: LeistungsdatenuebersichtFiltersToCols = {
        fach: 'fach',
        kurs: 'kurs',
        fachlehrer: 'lehrer',
        //Teilleistungen column hidden here for the time being (ticket 386)
        //teilleistungen: 'teilnoten',
        quartalnoten: "quartalnoten",
        note: 'note',
        mahnungen: 'istGemahnt',
        fehlstunden: 'fs',
        bemerkungen: 'fachbezogeneBemerkungen',
    };

    const getHiddenColumns = (toggles: Ref<TableColumnToggle>) => {
        for (const filter in toggles.value) {
            if (toggles.value[filter] === false) {
                hiddenColumns.value.add(filtersToCols[filter]);
            }
        }
    }

    //if a specific Leistung is clicked on and the function contains parameter "always === true"
    //or if the FbEditor is already open (aka "selectedLeistung !== null")
    //the FbEditor component displays Leistung data on the right side of the screen
    const selectedLeistung: Ref<Leistung | null> = ref(null);

    const selectLeistung = (leistung: Leistung, always: boolean = false): Leistung | null => {
        selectedLeistung.value = (selectedLeistung.value || always) ? leistung : null;
        if (selectedLeistung !== null) {
            return selectedLeistung.value;
        } return null
    }

    //check whether filters have receive some input from user
    const isFiltered = (): boolean => {
        return searchFilter.value !== null
            || klasseFilter.value.length > 0
            || jahrgangFilter.value.length > 0
            || fachFilter.value.length > 0
            || kursFilter.value.length > 0
            || noteFilter.value.length > 0;
    }

    //inputs are adjusted (for example, ToLowercase) and compared to data; results are returned
    const mapFilters = (): void => {
        klasseItems.value = mapFilterOptionsHelper(rows.value, 'klasse');
        fachItems.value = mapFilterOptionsHelper(rows.value, 'fach');
        kursItems.value = mapFilterOptionsHelper(rows.value, 'kurs');
        jahrgangItems.value = mapFilterOptionsHelper(rows.value, 'jahrgang');
        noteItems.value = Array.from(Object.values(allNotes.value));
    }

    const filterReset = (): void => {
        searchFilter.value = '';
        klasseFilter.value = [];
        jahrgangFilter.value = [];
        fachFilter.value = [];
        kursFilter.value = [];
        noteFilter.value = [];
    }

    const exportToFile = (): void => {
        exportDataToCSV(cols.value, hiddenColumns.value, rowsFiltered.value, 'Leistungsdaten');
    };

</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6
    }

    header #headline {
        @apply flex items-center justify-start gap-6 ml-6
    }

    .content-area {
        @apply mx-4 overflow-auto ml-6
    }

    .filter-area-icon {
        @apply -m-1.5
    }

    .export-button {
        @apply ml-6
    }
</style>
