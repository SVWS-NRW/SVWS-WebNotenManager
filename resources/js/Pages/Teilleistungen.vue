<template>
    <AppLayout title="Teilleistungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>

            <div class="content-area">
                <SvwsUiTable :items="rowsFiltered" :columns="cols" :toggle-columns="true" clickable count noDataText="" :sortByAndOrder= "{ key: 'klasse', order: true}"
                :filtered="isFiltered()" :filterReset="filterReset" :hiddenColumns="hiddenColumns" :filterOpen="false" :allowArrowKeySelection="true">

                    <template #filter>
                        <div class="filter-area-icon">
                            <SvwsUiButton @click="exportToFile()" type="transparent" size="big"
                                :class="'hover:opacity-100 focus-visible:opacity-100 export-button'">
                                <ri-download-2-line></ri-download-2-line>csv
                            </SvwsUiButton>
                        </div>
                    </template>
                    
                    <div class="filter-area"></div>
                        <template #filterAdvanced>
                            <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
                            <SvwsUiSelect label="Klasse" :items="klasseItems" :item-text="(item: any) => item" v-model="klasseFilter" @update:modelValue="dropdownsFilterHelper('klasse', klasseFilter)" />
                            <SvwsUiSelect label="Kurs" :items="kursItems" :item-text="(item: any) => item" v-model="kursFilter" @update:modelValue="dropdownsFilterHelper('kurs', kursFilter)"/>
                            <SvwsUiSelect label="Fach" :items="fachItems" :item-text="(item: any) => item" v-model="fachFilter" />
                        </template>

                    <!-- data from DB -->
                    <template #cell(klasse)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
                    </template>

                    <template #cell(name)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
                    </template>

                    <template #cell(fach)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
                    </template>

                    <template #cell(kurs)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
                    </template>

                    <template v-for="col in teilleistungCols" :key="col.key" v-slot:[`cell(${col.key})`]="{ value, rowData, rowIndex }">
                        <span>
                            <NoteInput column="note" :leistung="value" :teilleistung="true" :teilleistungId="col.id" :row-index="rowIndex" :disabled="!rowData.editable_teilnoten" @navigated="navigateTable" @updatedNavigableItems="updateNavigableItems"
                            ></NoteInput>
                        </span>
                    </template>

                    <template #cell(quartalnoten)="{ value, rowData, rowIndex }">
                        <NoteInput column="quartalnote" :leistung="rowData" :row-index="rowIndex" :disabled="!rowData.editable_noten" @navigated="navigateTable" @updatedNavigableItems="updateNavigableItems"
                        ></NoteInput>
                    </template>
                    
                    <template #cell(note)="{ value, rowData, rowIndex }">
                        <NoteInput column="note" :leistung="rowData" :row-index="rowIndex" :teilleistung="false" :disabled="!rowData.editable_noten" @navigated="navigateTable" @updatedNavigableItems="updateNavigableItems"
                        ></NoteInput>
                    </template>
                </SvwsUiTable>
            </div>
        </template>
    </AppLayout>
</template>


<script setup lang="ts">
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosResponse } from 'axios';
    import { computed, onMounted, Ref, ref } from 'vue';
    import { mapFilterOptionsHelper, selectHelper, searchHelper } from '@/Helpers/tableHelper';
    import { SvwsUiHeader, DataTableColumn, SvwsUiTable, SvwsUiSelect, SvwsUiTextInput, SvwsUiButton } from '@svws-nrw/svws-ui';
    import { NoteInput, BemerkungButton, } from '@/Components/Components';
    import { Teilleistung } from '@/Interfaces/Interface';
    import { updateNavigableItems, navigateTable } from '@/Helpers/tableNavigationHelper';
    import { exportDataToCSV } from '@/Helpers/exportHelper';

    interface FilterItem {
        [index: string]: {},
        klassen: {},
        kurse: {},
        selected: {
            kuerzel: string,
        },
    }

    interface RefMap {
        value: Map<string, Element>,
    }

    const title = 'Notenmanager - Teilleistungen';

    // Data received from DB
    const rows: Ref<Teilleistung[]|Teilleistung[]> = ref([]);

    const notes: Ref<string[]> = ref([]);

    const teilleistungCols: Ref<DataTableColumn[]> = ref([]); // Holds the fetched/refetched Columns

    // The different filters on top of the screen may get input and thus the data from DB will be filtered and then displayed
    const rowsFiltered = computed(() => {
        return rows.value.filter((teilleistung: Teilleistung): boolean => {
            if (searchFilter.value !== null || fachFilter.value !== "") {
                return searchHelper(teilleistung, ['name'], searchFilter.value)
                && selectHelper(teilleistung, 'fach', fachFilter.value);
            }
            return true;
        })
    });

    // some columns may be displayed/hidden on demand
    const toggles = ref({
        fach: false,
        kurs: false,
        quartalnoten: false,
        note: false,
    });

    // Api Call - get default option for teilleistungen display
    onMounted((): Promise<void> => axios
        .get(route('teilleistungen.index', "filteredTeilleistungen"))
        .then((response: AxiosResponse): void => {
            rows.value = response.data.leistungen; // Fetches leistungen
            teilleistungCols.value = response.data.columns; // Fetches the columns
            notes.value = response.data.notes;
            toggles.value = response.data.toggles;
            filterItems.value = response.data.filters;
            klasseFilter.value = filterItems.value!.selected.kuerzel;
            addTeilleistungen(teilleistungCols);
        })
        .finally((): void => {
            mapFilters();
        })
    );

    // Standard-Spalten für die Tabelle
    const default_cols : DataTableColumn[] = [
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, fixedWidth: 6, disabled: false, toggleInvisible:true },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 16, disabled: false, toggleInvisible:true },
    ];

    const cols: Ref<DataTableColumn[]> = ref([
        ...default_cols,
        { key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, toggle: true },
        { key: 'kurs', label: 'Kurs', sortable: true, span: 1, minWidth: 5, toggle: true },
        { key: 'quartalnoten', label: 'Quartal', sortable: true, span: 1, minWidth: 6, toggle: true },
        { key: 'note', label: 'Note', sortable: true, span: 2, minWidth: 5, toggle: true },
    ]);

    //filters from settings or user settings determine whether columns are hidden or shown in the table
    const hiddenColumns = ref<Set<string>>(new Set<string>());

    // push the fetched TL Columns to global columns after Kurs
    const addTeilleistungen = (teilleistungCols: Ref<DataTableColumn[]>)  => {
    // reverse order for display because TLs are received from newest to latest atm
        teilleistungCols.value.reverse().forEach((c) => itemRefsTLNoteInputList.value[c.id] = ref(new Map()));
        teilleistungCols.value.reverse().forEach((c) => cols.value.splice(4, 0, c)); 
        teilleistungCols.value.reverse()[teilleistungCols.value.length - 1].span = 4;
        
    }

    // Filter
    const searchFilter: Ref<string|null> = ref(null);
    const klasseFilter: Ref <string> = ref("");
    const kursFilter: Ref <string> = ref("");
    const fachFilter: Ref <string> = ref("");

    //filterItems comes from controller and includes 3 objects (klasse, kurs and selected)
    const filterItems = ref<FilterItem>();
    const klasseItems = ref(new Map());
    const kursItems = ref(new Map());
    const fachItems: Ref<string[]> = ref([]);

    const dropdownsFilterHelper = (filterName: string, filterValue: string) => axios
        .get(route('teilleistungen.get_'+ filterName, filterValue))
        .then((response: AxiosResponse): void => {
            rows.value = response.data.leistungen; // Fetches leistungen
            teilleistungCols.value = response.data.columns; // Fetches columns
        })
        .finally((): void => {
            mapFilters();
        });

    const filterReset = (): void => {
        axios.get(route('teilleistungen.index', "unfilteredTeilleistungen"))
        .then((response: AxiosResponse): void => {
            rows.value = response.data.leistungen; // Fetches leistungen
            //console.log(rows.value);
            teilleistungCols.value = response.data.columns; // Fetches the columns
            notes.value = response.data.notes;
            toggles.value = response.data.toggles;
            filterItems.value = response.data.filters;
        })
        .finally((): void => {
            mapFilters();
            searchFilter.value = "";
            klasseFilter.value = "";
            kursFilter.value = "";
            fachFilter.value = "";
        })
    }

    const isFiltered = (): boolean => {
        return searchFilter.value !== null
        || klasseFilter.value !== ""
        || kursFilter.value !== ""
        || fachFilter.value !== ""
    }

    // dropdowns in header
    const mapFilters = (): void => {
        //klasse and kurs come separately from controller (together with "selected") while fach is fetched from DB results
        klasseItems.value = new Map(Object.entries(filterItems.value!.klassen));
        kursItems.value = new Map(Object.entries(filterItems.value!.kurse));
        fachItems.value = mapFilterOptionsHelper(rows.value, 'fach');
    };

    const exportToFile = (): void => {
        exportDataToCSV(cols.value, hiddenColumns.value, rowsFiltered.value, 'Teilleistungen');
    };

</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6
    }

    .content-area {
        @apply mx-4 overflow-auto ml-6
    }

    .filter-area-icon {
        @apply -m-1.5
    }
</style>

