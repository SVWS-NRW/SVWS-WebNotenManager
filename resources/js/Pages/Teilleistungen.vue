<template>
    <AppLayout title="Teilleistungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>

            <!-- TODO: remove unnecessary elements if present -->
            <div class="content-area">
                <SvwsUiTable :items="rowsFiltered" :columns="cols" :toggle-columns="true" clickable count noDataText="" :sortByAndOrder= "{ key: 'klasse', order: true}"
                :filtered="isFiltered()" :filterReset="filterReset" :hiddenColumns="hiddenColumns" :filterOpen="false">

                    <template #filter>
                        <div class="filter-area-icon">
                            <SvwsUiButton @click="exportToFile()" type="transparent" size="big"
                                :class="'hover:opacity-100 focus-visible:opacity-100 export-button'">
                                <ri-download-2-line></ri-download-2-line>csv
                            </SvwsUiButton>
                        </div>
                    </template>

                    <!-- Erweiterte Filteroptionen -->
                    <div class="filter-area"></div>
                        <template #filterAdvanced>
                            <SvwsUiSelect label="Klasse" :items="klasseItems" :item-text="item => item" v-model="klasseFilter" />
                            <SvwsUiSelect label="Kurs" :items="kursItems" :item-text="item => item" v-model="kursFilter" />
                            <SvwsUiSelect label="Fach" :items="fachItems" :item-text="item => item" v-model="fachFilter" />
                        </template>

                    <!-- Individuelle Zellen-Template -->
                    <!-- BemerkungButton in der Zelle 'klasse' -->
                    <template #cell(klasse)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'name' -->
                    <template #cell(name)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'fach' -->
                    <template #cell(fach)="{ value, rowData }">
                        <!-- <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" /> -->
                        {{ value }} 
                    </template>

                    <!-- BemerkungButton in der Zelle 'kurs' -->
                    <template #cell(kurs)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
                    </template>
                    <!-- TODO: add rest -->
                    <!-- TODO: check rights and components used for all of them -->
                     <!-- this is a dummy taking data from api.noten/NoteInput -->
                    <template #cell(ka_1)="{ value, rowData, rowIndex }">
                        <NoteInput :leistung="rowData" :disabled="!rowData.editable.noten" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs"
                        ></NoteInput>
                    </template>
                    <template #cell(ka_2)="{ value, rowData, rowIndex }">
                        {{ value }} 
                    </template>
                    <template #cell(ka_3)="{ value, rowData, rowIndex }">
                        {{ value }} 
                    </template>
                    <template #cell(ka_4)="{ value, rowData, rowIndex }">
                        {{ value }} 
                    </template>
                    <template #cell(somi1)="{ value, rowData, rowIndex }">
                        {{ value }} 
                    </template>
                    <template #cell(somi2)="{ value, rowData, rowIndex }">
                        {{ value }} 
                    </template>
                    <!-- TODO: ticket 260; what comes from db not ready yet -->
                    <template #cell(quartalnoten)="{ value, rowData, rowIndex }">
                        <NoteInput :leistung="rowData" :disabled="!rowData.editable.noten" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs"
                        ></NoteInput>
                    </template>
                    <!-- BemerkungButton in der Zelle 'note' -->
                    <template #cell(note)="{ value, rowData, rowIndex }">
                        <NoteInput :leistung="rowData" :disabled="!rowData.editable.noten" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs"
                        ></NoteInput>
                    </template>
                </SvwsUiTable>
            </div>
        </template>
    </AppLayout>
</template>


<script setup lang="ts">
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosPromise, AxiosResponse } from 'axios';
    // TODO: refactor unnecessary elements
    import { computed, onMounted, Ref, ref } from 'vue';
    import { mapFilterOptionsHelper, multiSelectHelper } from '@/Helpers/tableHelper';
    import { SvwsUiHeader, DataTableColumn, SvwsUiTable, SvwsUiSelect, SvwsUiTextInput, SvwsUiButton } from '@svws-nrw/svws-ui';
    import { NoteInput, BemerkungButton, } from '@/Components/Components';
    import { Leistung, Teilleistung, TableColumnToggle } from '@/Interfaces/Interface';
    import { exportDataToCSV } from '@/Helpers/exportHelper';

    //TODO: apply when backend ready
    //Correlation filter names and column names on this page
    interface teillestungenFiltersToCols {
        [index: string]: string,
        klasse: string,
        kurs: string,
        fach: string,
    };

    const title = 'Notenmanager - Teilleistungen';

    // TODO: build display elements and functions when backend is ready (this is a dummy so far)
    //TODO: Refactoring? -> call all this from a helper for all tables?
    //rows will receive a reference map which will allow navigation within the three input columns of MeinUnterricht
    const itemRefsNoteInput = ref(new Map());
    const itemRefsKa_1 = ref(new Map());
    const itemRefsKa_2 = ref(new Map());

    // Data received from DB
    const rows: Ref<Teilleistung[]> = ref([]);

    //TODO: we are working with select, so filter for multiselect won't work here
    // The different filters on top of the screen may get input and thus the data from DB will be filtered and then displayed
    const rowsFiltered = computed(() => {
        return rows.value.filter((teilleistung) => {
            return tableFilter(teilleistung, klasseFilter.value, "klasse")
            && tableFilter(teilleistung, kursFilter.value, "kurs")
            && tableFilter(teilleistung, fachFilter.value, "fach");
        })
    });

    //TODO: interface
    // some columns may be displayed/hidden on demand
    const toggles = ref({
        ka_1: false,
        ka_2: false,
        ka_3: false,
        ka_4: false,
        fach: false,
        kurs: false,
        quartalnoten: false,
        note: false,
    });

    // all notes present in the noten DB table
    const allNotes: Ref<string[]> = ref([]);

    // Api Call - Daten für meinUnterricht
    onMounted((): Promise<void> => axios
        .get(route('api.mein_unterricht'))
        .then((response: AxiosResponse): void => {
            rows.value = response.data.data;
            toggles.value = response.data.toggles;
            allNotes.value = response.data.allNotes;
            getHiddenColumns(toggles);
        })
        .finally((): void => mapFilters())
    );

    // Standard-Spalten für die Tabelle
    const default_cols : DataTableColumn[] = [
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, fixedWidth: 6, disabled: false, toggleInvisible:true },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 16, disabled: false, toggleInvisible:true },
    ];

    const cols: Ref<DataTableColumn[]> = ref([
        ...default_cols,
        { key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, toggleInvisible:true  },
        { key: 'kurs', label: 'Kurs', sortable: true, span: 2, minWidth: 5, toggleInvisible:true  },
        { key: 'ka_1', label: 'KA_1', sortable: false, span: 1, minWidth: 6, toggle: true  },
        { key: 'ka_2', label: 'KA_2', sortable: false, span: 1, minWidth: 6, toggle: true  },
        { key: 'ka_3', label: 'KA_3', sortable: false, span: 1, minWidth: 6, toggle: true  },
        { key: 'ka_4', label: 'KA_4', sortable: false, span: 1, minWidth: 6, toggle: true  },
        { key: 'somi1', label: 'Somi1', sortable: false, span: 1, minWidth: 6, toggle: true  },
        { key: 'somi2', label: 'Somi2', sortable: false, span: 1, minWidth: 6, toggle: true  },
        { key: 'quartalnoten', label: 'Quartal', sortable: true, span: 1, minWidth: 6, toggle: true },
        { key: 'note', label: 'Note', sortable: true, span: 2, minWidth: 5, toggle: true },
    ]);

    //TODO: interface
    //filters from settings or user settings determine whether columns are hidden or shown in the table
    const hiddenColumns = ref<Set<string>>(new Set<string>());
    //filter names from DB do not match our cols; TODO: check whether it may be corrected at some point
    const teilleistungenFiltersToCols = {
        //and the rest
        klasse: 'klasse',
        kurs: 'kurs',
    };

    //TODO: adjust typing
    const tableFilter = (teilleistung, filterValue: string, column: string): boolean => {
        if (filterValue == "") return true;
        return teilleistung[column] == filterValue;
    }

    //TODO: check if necessary
    //TODO: typing
    const getHiddenColumns = (toggles) => {
        for (const filter in toggles.value) {
            if (toggles.value[filter] === false) {
                hiddenColumns.value.add(teilleistungenFiltersToCols[filter]);
            }
        }
    }

    // Filter
    const klasseFilter: Ref <string> = ref("");
    const kursFilter: Ref <string> = ref("");
    const fachFilter: Ref <string> = ref("");

    //TODO: do we need this?
    const klasseItems: Ref<string[]> = ref([]);
    const kursItems: Ref<string[]> = ref([]);
    const fachItems: Ref<string[]> = ref([]);

    //TODO: check if filterReset works with uiSelect as well
    const filterReset = (): void => {
        klasseFilter.value = "";
        kursFilter.value = "";
        fachFilter.value = "";
    }

    const isFiltered = (): boolean => {
        return klasseFilter.value !== "" || kursFilter.value !== "" || fachFilter.value !== ""
    }

    // Filteroptionen mappen
    const mapFilters = (): void => {
        klasseItems.value = mapFilterOptionsHelper(rows.value, 'klasse');
        kursItems.value = mapFilterOptionsHelper(rows.value, 'kurs');
        fachItems.value = mapFilterOptionsHelper(rows.value, 'fach');
    };

    //input html element and reference map name are determined by child
    function updateItemRefs(rowIndex: number, el: Element, itemRefsName: string): void {
        switch (itemRefsName) {
            case "itemRefsNoteInput":
                itemRefsNoteInput.value.set(rowIndex, el);
                break;
            case "itemRefsKa_1":
                itemRefsKa_1.value.set(rowIndex, el);
                break;
            // and so on
            default:
                console.log("Map not found.")
        }
	}

    //table navigation actions (go up/down within the column)
	function next(id: number, itemRefs: Ref) {
		const el = itemRefs.value.get(id + 1);
		if (el)
            el.input.select();
	}

	const previous = (id: number, itemRefs: Ref) => {
        const el = itemRefs.value.get(id - 1);
		if (el)
        el.input.select();
	}

    //direction (up/down within the column) and map name are received from child component
    const navigateTable = (direction: string, rowIndex: number, itemRefsName: string): void => {
        switch (itemRefsName) {
            case "itemRefsNoteInput":
                direction === "next" ? next(rowIndex, itemRefsNoteInput) : previous(rowIndex, itemRefsNoteInput);
                break;
            case "itemRefsKa_1":
                direction === "next" ? next(rowIndex, itemRefsfs) : previous(rowIndex, itemRefsfs);
                break;
            // and so on
            default:
                console.log("itemRefs map not found");
        }	
	}    

    const exportToFile = (): void => {
        exportDataToCSV(cols.value, hiddenColumns.value, rowsFiltered.value, 'Teilleistungen');
    };

</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6 ml-6
    }

    .content-area {
        @apply mx-4 overflow-auto ml-6
    }

    .filter-area-icon {
        @apply -m-1.5
    }
</style>

