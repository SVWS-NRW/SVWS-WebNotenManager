<template>
    <AppLayout title="Teilleistungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>

            <!-- TODO: remove whatever is unnecessary -->
            <div class="content-area">
                <SvwsUiTable :items="rowsFiltered" :columns="cols" :toggle-columns="true" clickable count noDataText="" :sortByAndOrder= "{ key: 'klasse', order: true}"
                :filtered="isFiltered()" :filterReset="filterReset" :hiddenColumns="hiddenColumns" :filterOpen="false">

                    <!-- Erweiterte Filteroptionen -->
                    <template #filterAdvanced>
                        <SvwsUiSelect label="Klasse" :items="klasseItems" :item-text="item => item" v-model="klasseFilter" />
                        <SvwsUiSelect label="Kurs" :items="kursItems" :item-text="item => item" v-model="kursFilter" />
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
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'kurs' -->
                    <template #cell(kurs)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
                    </template>

                    <!-- TODO: ticket 260; nothing comes from db yet -->
                    <template #cell(quartal)="{ value, rowData, rowIndex }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" />
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
    // TODO: import what's neeeded and remove what is not
    import { computed, onMounted, Ref, ref } from 'vue';
    import { mapFilterOptionsHelper, multiSelectHelper } from '@/Helpers/tableHelper';
    import { SvwsUiHeader, DataTableColumn, SvwsUiTable, SvwsUiSelect, SvwsUiTextInput } from '@svws-nrw/svws-ui';
    import { NoteInput, BemerkungButton, } from '@/Components/Components';
    import { Leistung, Teilleistung, TableColumnToggle } from '@/Interfaces/Interface';

    //TODO: check it
    //Correlation filter names and column names on this page
    interface teillestungenFiltersToCols {
        [index: string]: string,
        klasse: string,
        kurs: string,
    };

    const title = 'Notenmanager - Teilleistungen';

    // TODO: build display elements and functions when backend is ready (this is a dummy so far)
    //rows will receive a reference map which will allow navigation within the three input columns of MeinUnterricht
    const itemRefsNoteInput = ref(new Map());
    const itemRefsfs = ref(new Map());
    const itemRefsfsu = ref(new Map());

    // Data received from DB
    const rows: Ref<Teilleistung[]> = ref([]);

    //TODO: we are working with select, so filter for multiselect won't work here
    // The different filters on top of the screen may get input and thus the data from DB will be filtered and then displayed
    const rowsFiltered = computed(() => {
        return rows.value.filter((teilleistung) => {
            return tableFilter(teilleistung, klasseFilter.value, "klasse")
            && tableFilter(teilleistung, kursFilter.value, "kurs");
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
        { key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, toggle: true  },
        { key: 'kurs', label: 'Kurs', sortable: true, span: 2, minWidth: 5, toggle: true  },
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
    //TODO: types
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

    //TODO: do we need this?
    const klasseItems: Ref<string[]> = ref([]);
    const kursItems: Ref<string[]> = ref([]);

    //TODO: check if filterReset works with uiSelect as well
    const filterReset = (): void => {
        klasseFilter.value = "";
        kursFilter.value = "";
    }

    const isFiltered = (): boolean => {
        //still -> || kursFilter.value !== ""
            return klasseFilter.value !== "" || kursFilter.value !== ""
    }

    // Filteroptionen mappen
    const mapFilters = (): void => {
        klasseItems.value = mapFilterOptionsHelper(rows.value, 'klasse');
        kursItems.value = mapFilterOptionsHelper(rows.value, 'kurs');
    };

    //input html element and reference map name are determined by child
    function updateItemRefs(rowIndex: number, el: Element, itemRefsName: string): void {
        switch (itemRefsName) {
            case "itemRefsNoteInput":
                itemRefsNoteInput.value.set(rowIndex, el);
                break;
            case "itemRefsfs":
                itemRefsfs.value.set(rowIndex, el);
                break;
            case "itemRefsfsu":
                itemRefsfsu.value.set(rowIndex, el);
                break;
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

    //TODO: do we want this functionality too here? if so, it would need to be adjusted for Teilleistungen
    //direction (up/down within the column) and map name are received from child component
    const navigateTable = (direction: string, rowIndex: number, itemRefsName: string): void => {
        switch (itemRefsName) {
            // case "itemRefsNoteInput":
            //     direction === "next" ? next(rowIndex, itemRefsNoteInput) : previous(rowIndex, itemRefsNoteInput);
            //     break;
            // case "itemRefsfs":
            //     direction === "next" ? next(rowIndex, itemRefsfs) : previous(rowIndex, itemRefsfs);
            //     break;
            // case "itemRefsfsu":
            //     direction === "next" ? next(rowIndex, itemRefsfsu) : previous(rowIndex, itemRefsfsu);
            //     break;
            // default:
            //     console.log("itemRefs map not found");
        }	
	}    


</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6 ml-6
    }

    .content-area {
        @apply mx-4 overflow-auto ml-6
    }
</style>

