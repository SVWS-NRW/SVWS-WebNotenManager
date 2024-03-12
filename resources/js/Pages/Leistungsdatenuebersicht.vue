<template>
    <Head>
        <title>Notenmanager - Leistungsdatenübersicht</title>
    </Head>

    <AppLayout>
         <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">{{ title }}</h2>
                </div>
            </header>
            <div class="content-area">
                <SvwsUiTable :items="rowsFiltered" :columns="cols" clickable count :filtered="isFiltered()" :filterReset="filterReset"  
                    :filterOpen="true" :sortByAndOrder="{ key: 'klasse', order: true}">
                    <template #filter>
                        <div class="edition-pencil-button">
                            <SvwsUiButton @click="leistungEditableToggle()" v-if="lehrerCanOverrideFachlehrer" 
                                :type="leistungEditable ? 'primary' : 'secondary'" size="big">
                                <ri-pencil-fill></ri-pencil-fill>
                            </SvwsUiButton>
                        </div>
                        <SvwsUiCheckbox v-model="toggles.fach" :value="true">Fach</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.kurs" :value="true">Kurs</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.note" :value="true">Note</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.fachlehrer" :value="true">Fachlehrer</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.mahnungen" :value="true">Mahnungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.fehlstunden" :value="true">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.bemerkungen" :value="true">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                        <SvwsUiButton class="export-button" type="secondary" @click="exportToFile('csv')">CSV</SvwsUiButton>
                    </template>
                    <template #filterAdvanced>
                        <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
                        <SvwsUiMultiSelect label="Klasse" :items="klasseItems" :item-text="item => item" v-model="klasseFilter" />
                        <SvwsUiMultiSelect label="Jahrgang" :items="jahrgangItems" :item-text="item => item" v-model="jahrgangFilter" />
                        <SvwsUiMultiSelect label="Fach" :items="fachItems" :item-text="item => item" v-model="fachFilter" />
                        <SvwsUiMultiSelect label="Kurs" :items="kursItems" :item-text="item => item" v-model="kursFilter" />
                        <SvwsUiMultiSelect label="Note" :items="noteItems" :item-text="item => item" v-model="noteFilter" />
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
                        <NoteInput :leistung="rowData" :disabled="inputDisabled(rowData.editable.noten)" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs" />
                    </template>
                    <template #cell(istGemahnt)="{ value, rowData }">
                        <MahnungIndicator :leistung="rowData" :disabled="inputDisabled(rowData.editable.mahnungen)" />
                    </template>

                    <template #cell(fs)="{ value, rowData, rowIndex }">
                        <FehlstundenInput column="fs" :model="rowData" :disabled="inputDisabled(rowData.editable.fehlstunden)" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs" />
                    </template>

                    <template #cell(fsu)="{ value, rowData, rowIndex }">
                        <FehlstundenInput column="fsu" :model="rowData" :disabled="inputDisabled(rowData.editable.fehlstunden)" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs" />
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
    import { computed, onMounted, ref, Ref } from 'vue';
    import axios, { AxiosPromise, AxiosResponse } from 'axios';
    import { Leistung, TableColumnToggle } from '@/Interfaces/Interface';
    import { mapFilterOptionsHelper, multiSelectHelper, searchHelper } from '@/Helpers/tableHelper';
    import { DataTableColumn, SvwsUiTable, SvwsUiCheckbox, SvwsUiTextInput, SvwsUiMultiSelect, SvwsUiButton, } from '@svws-nrw/svws-ui';
    import { BemerkungButton, BemerkungIndicator, FbEditor, FehlstundenInput, MahnungIndicator, NoteInput, } from '@/Components/Components';
    import { mapToggleToDatabaseField } from '@/Helpers/columnMappingHelper';
    import { handleExport } from '@/Helpers/exportHelper';

    const title = 'Notenmanager - Leistungsdatenübersicht';

    //rows will receive a reference map which will allow navigation within the three input columns of MeinUnterricht
    const itemRefsNoteInput = ref(new Map());
    const itemRefsfs = ref(new Map());
    const itemRefsfsu = ref(new Map());

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
        if (true === lehrerCanOverrideFachlehrer.value) {
            leistungEditable.value = !leistungEditable.value;
        }
    };

    //these columns can be hidden/displayed on the page, which can overwrite the platform general settings under Einstellungen/Filter
    const toggles: Ref<TableColumnToggle> = ref({
        teilleistungen: false,
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

    const inputDisabled = (condition: boolean): boolean => !(condition && leistungEditable.value);

    onMounted((): Promise<void> => axios
        .get(route('api.leistungsdatenuebersicht'))
        .then((response: AxiosResponse): void => {
            rows.value = response.data.data;
            toggles.value = response.data.toggles;
            lehrerCanOverrideFachlehrer.value = response.data.lehrerCanOverrideFachlehrer;
        })
        .finally((): void => mapFilters())
    );

    // columns used for sorting the data
    const default_cols : DataTableColumn[] = [
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, fixedWidth: 6, disabled: false },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 16, disabled: false },
    ];

    // the other columns received from DB
    const cols = computed((): DataTableColumn[] => {
        const result = [...default_cols];
        if (toggles.value.fach)
            result.push({ key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, disabled: false });
        if (toggles.value.kurs)
            result.push({ key: 'kurs', label: 'Kurs', sortable: true, span: 2, minWidth: 5, disabled: false });
        if (toggles.value.fachlehrer)
            result.push({ key: 'lehrer', label: 'Fachlehrer', sortable: true, span: 2, minWidth: 7 });        
        if (toggles.value.teilleistungen)
            result.push({ key: 'teilnoten', label: 'Teilnoten', sortable: true, span: 5, minWidth: 6 });
        if (toggles.value.note)
            result.push({ key: 'note', label: 'Note', sortable: true, span: 1, minWidth: 6 });
        if (toggles.value.mahnungen)
            result.push({ key: 'istGemahnt', label: 'Mahnungen', sortable: true, span: 1, minWidth: 8});
        if (toggles.value.fehlstunden) {
            result.push({ key: 'fs', label: 'FS', sortable: true, span: 1, minWidth: 6 });
            result.push({ key: 'fsu', label: 'FSU', sortable: true, span: 1, minWidth: 6 });
        }
        if (toggles.value.bemerkungen)
            result.push({ key: 'fachbezogeneBemerkungen', label: 'FB', sortable: true, span: 12, minWidth: 4 });
        return result;
    });

    //if a specific Leistung is clicked on and the function contains parameter "always === true"
    //or if the FbEditor is already open (aka "selectedLeistung !== null")
    //the FbEditor component displays Leistung data on the right side of the screen
    const selectedLeistung: Ref<Leistung | null> = ref(null);

    const selectLeistung = (leistung: Leistung, always: boolean = false): Leistung | null => {
        selectedLeistung.value = (selectedLeistung.value || always) ? leistung : null;
        // if (selectedLeistung !== null) {
        //     return selectedLeistung.value;
        // } return null
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
        noteItems.value = mapFilterOptionsHelper(rows.value, 'note');
    }

    const filterReset = (): void => {
        searchFilter.value = '';
        klasseFilter.value = [];
        jahrgangFilter.value = [];
        fachFilter.value = [];
        kursFilter.value = [];
        noteFilter.value = [];
    }

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
            el.input.focus();
	}

	const previous = (id: number, itemRefs: Ref) => {
        const el = itemRefs.value.get(id - 1);
		if (el)
        el.input.focus();
	}

    //direction (up/down within the column) and map name are received from child component
    const navigateTable = (direction: string, rowIndex: number, itemRefsName: string): void => {
        switch (itemRefsName) {
            case "itemRefsNoteInput":
                direction === "next" ? next(rowIndex, itemRefsNoteInput) : previous(rowIndex, itemRefsNoteInput);
                break;
            case "itemRefsfs":
                direction === "next" ? next(rowIndex, itemRefsfs) : previous(rowIndex, itemRefsfs);
                break;
            case "itemRefsfsu":
                direction === "next" ? next(rowIndex, itemRefsfsu) : previous(rowIndex, itemRefsfsu);
                break;
            default:
                console.log("itemRefs map not found");
        }	
	}

    /**
     * Exportiert Daten in einer Datei im angegebenen Format (CSV oder Excel).
     * TODO: explain how here and delete duplicated comments
     * @param type - Der Exporttyp ('csv' oder 'excel').
     */
     const exportToFile = (type: string): void => {
        // Bestimme die zu exportierenden Spalten basierend auf den Benutzereinstellungen
        const visibleColumns: string[] = [
            "klasse",
            "nachname",
            "vorname",
            ...Object.keys(toggles.value).filter((col: string) => toggles.value[col as keyof TableColumnToggle])
        ];
        
        // Ordne sichtbare Spalten den entsprechenden Datenbankfeldern zu
        const mappedColumns: string[] = visibleColumns.map((col: string) => mapToggleToDatabaseField(col as keyof TableColumnToggle));

        // Bereite Daten für den Export vor, indem relevante Spalten ausgewählt werden
        const exportData = rowsFiltered.value.map((row: Leistung) => {
            const rowData: Record<string, any> = {};
            mappedColumns.forEach((col: string) => {
                // Überprüfe, ob die Spalte 'istGemahnt' ist und die Werte auf 'ja' oder 'nein' abbilde
                if (col === 'istGemahnt')
                    rowData[col] = row[col as keyof Leistung] ? 'ja' : 'nein';
                else
                    rowData[col] = row[col as keyof Leistung];
            });
            return rowData;
        });

        // Rufe den allgemeinen Export-Handler mit den vorbereiteten Daten auf
        handleExport(exportData, type, 'leistungsdatenübersicht');
    }

</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6
    }

    .edition-pencil-button {
        @apply mr-4
    }

    header #headline {
        @apply flex items-center justify-start gap-6
    }

    .content-area {
        @apply mx-4 overflow-auto
    }

    .export-button {
        @apply ml-6
    }
</style>