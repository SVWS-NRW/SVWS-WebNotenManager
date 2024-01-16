<template>
    <!-- Seitentitel -->
    <Head>
        <title>Mein Unterricht</title>
    </Head>
    <AppLayout>
        <template #main>
            <header>
                <!-- Überschrift des Headers -->
                <div id="headline">
                    <h2 class="text-headline">{{ title }}</h2>
                </div>
            </header>
            <div class="content-area">
                <!-- Tabelle mit SvwsUiTable -->
                <SvwsUiTable :items="rowsFiltered" :columns="cols" clickable count :sortByAndOrder= "{ key: 'klasse', order: true}"
                    :filtered="isFiltered()" :filterReset="filterReset" filterOpen>

                    <!-- Basis-Filteroptionen -->
                    <template #filter>
                        <SvwsUiCheckbox v-model="toggles.fach" :value="true">Fach</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.kurs" :value="true">Kurs</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.note" :value="true">Note</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.mahnungen" :value="true">Mahnungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.fehlstunden" :value="true">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.bemerkungen" :value="true">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                        <SvwsUiButton class="export-button" type="transparent" @click="exportToFile('csv')">CSV</SvwsUiButton>
                        <SvwsUiButton class="export-button" type="transparent" @click="exportToFile('excel')">Excel</SvwsUiButton>
                    </template>

                    <!-- Erweiterte Filteroptionen -->
                    <template #filterAdvanced>
                        <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
                        <SvwsUiMultiSelect label="Klasse" :items="klasseItems" :item-text="item => item" v-model="klasseFilter" />
                        <SvwsUiMultiSelect label="Jahrgang" :items="jahrgangItems" :item-text="item => item" v-model="jahrgangFilter" />
                        <SvwsUiMultiSelect label="Fach" :items="fachItems" :item-text="item => item" v-model="fachFilter" />
                        <SvwsUiMultiSelect label="Kurs" :items="kursItems" :item-text="item => item" v-model="kursFilter" />
                        <SvwsUiMultiSelect label="Note" :items="noteItems" :item-text="item => item" v-model="noteFilter" />
                    </template>

                    <!-- Individuelle Zellen-Template -->
                    <!-- BemerkungButton in der Zelle 'klasse' -->
                    <template #cell(klasse)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'name' -->
                    <template #cell(name)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'fach' -->
                    <template #cell(fach)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'kurs' -->
                    <template #cell(kurs)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'note' -->
                    <template #cell(note)="{ value, rowData }">
                        <NoteInput :leistung="rowData" :disabled="false" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'istGemahnt' -->
                    <template #cell(istGemahnt)="{ value, rowData }">
                        <MahnungIndicator :leistung="rowData" :disabled="!rowData.editable.mahnungen" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'fs' -->
                    <template #cell(fs)="{ value, rowData }">
                        <FehlstundenInput column="fs" :model="rowData" :disabled="!rowData.editable.fehlstunden"/>
                    </template>

                    <!-- BemerkungButton in der Zelle 'fsu' -->
                    <template #cell(fsu)="{ value, rowData }">
                        <FehlstundenInput column="fsu" :model="rowData" :disabled="!rowData.editable.fehlstunden"/>
                    </template>

                    <!-- BemerkungButton in der Zelle 'fachbezogeneBemerkungen' -->
                    <template #cell(fachbezogeneBemerkungen)="{ value, rowData }">
                        <BemerkungIndicator :model="rowData" :bemerkung="rowData['fachbezogeneBemerkungen']"
                            @clicked="selectLeistung(rowData, true)" floskelgruppe="fb" />
                    </template>
                </SvwsUiTable>
            </div>
        </template>

        <!-- Anzeige des ausgewählten Elements -->
        <template v-slot:aside v-if="selectedLeistung">
            <FbEditor :leistung="selectedLeistung" @updated="selectedLeistung.fachbezogeneBemerkungen = $event;"
                @close="selectedLeistung = null" editable />
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
    import { computed, onMounted, Ref, ref } from 'vue';
    import axios, { AxiosPromise, AxiosResponse } from 'axios';
    import { Head } from '@inertiajs/inertia-vue3';
    import { Leistung, TableColumnToggle } from '@/Interfaces/Interface';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { mapFilterOptionsHelper, multiSelectHelper, searchHelper } from '@/Helpers/tableHelper';
    import {
        DataTableColumn, SvwsUiTable, SvwsUiCheckbox, SvwsUiTextInput, SvwsUiMultiSelect, SvwsUiButton,
    } from '@svws-nrw/svws-ui';
    import {
        BemerkungIndicator, MahnungIndicator, NoteInput, FehlstundenInput, FbEditor, BemerkungButton,
    } from '@/Components/Components';

    // Seitentitel
    const title = 'Notenmanager - mein Unterricht';

    // Daten
    const rows: Ref<Leistung[]> = ref([]);

    // Gefilterte Daten
    const rowsFiltered = computed((): Leistung[] => {
        return rows.value.filter((leistung: Leistung): boolean => {
            return searchHelper(leistung, ['name'], searchFilter.value || '')
                && multiSelectHelper(leistung, 'klasse', klasseFilter.value)
                && multiSelectHelper(leistung, 'fach', fachFilter.value)
                && multiSelectHelper(leistung, 'kurs', kursFilter.value)
                && multiSelectHelper(leistung, 'jahrgang', jahrgangFilter.value)
                && multiSelectHelper(leistung, 'note', noteFilter.value)
        })
    });

    // Schalter für Tabellenspalten
    const toggles: Ref<TableColumnToggle> = ref({
        teilleistungen: false,
        mahnungen: false,
        bemerkungen: false,
        fehlstunden: false,
        kurs: false,
        note: false,
        fach: false,
    });

    // Api Call - Daten für meinUnterricht
    onMounted((): void => axios
        .get(route('api.mein_unterricht'))
        .then((response: AxiosResponse): void => {
            rows.value = response.data.data;
            toggles.value = response.data.toggles;
        })
        .finally((): void => mapFilters())
    );

    // Standard-Spalten für die Tabelle
    const default_cols : DataTableColumn[] = [
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, fixedWidth: 6, disabled: false },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 10, disabled: false },
    ];

     // Ein- und Ausblendbare Spalten
    const cols = computed((): DataTableColumn[] => {
        const result = [...default_cols];
        if (toggles.value.fach)
            result.push({ key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, disabled: false });
        if (toggles.value.kurs)
            result.push({ key: 'kurs', label: 'Kurs', sortable: true, span: 2, minWidth: 5, disabled: false });
        if (toggles.value.teilleistungen)
            result.push({ key: 'teilnoten', label: 'Teilnoten', sortable: true, span: 5, minWidth: 15 });
        if (toggles.value.note)
            result.push({ key: 'note', label: 'Note', sortable: true, span: 1, minWidth: 5 });
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

    // ...
    const selectedLeistung: Ref<Leistung | null> = ref(null);

    // ...
    const selectLeistung = (leistung: Leistung, always: boolean = false): Leistung | null =>
        selectedLeistung.value = (selectedLeistung.value || always) ? leistung : null;

    // ...
    const searchFilter: Ref<string|null> = ref(null);
    const klasseFilter: Ref <string[]> = ref([]);
    const fachFilter: Ref <string[]> = ref([]);
    const kursFilter: Ref <string[]> = ref([]);
    const jahrgangFilter: Ref <string[]> = ref([]);
    const noteFilter: Ref <string[]> = ref([]);

    const klasseItems: Ref<string[]> = ref([]);
    const fachItems: Ref<string[]> = ref([]);
    const kursItems: Ref<string[]> = ref([]);
    const jahrgangItems: Ref<string[]> = ref([]);
    const noteItems: Ref<string[]> = ref([]);

    // Filter zurücksetzen
    const filterReset = (): void => {
        searchFilter.value = '';
        klasseFilter.value = [];
        jahrgangFilter.value = [];
        fachFilter.value = [];
        kursFilter.value = [];
        noteFilter.value = [];
    }

    // Prüfen, ob Filter aktiv sind
    const isFiltered = (): boolean => {
        return searchFilter.value !== null
            || klasseFilter.value.length > 0
            || jahrgangFilter.value.length > 0
            || fachFilter.value.length > 0
            || kursFilter.value.length > 0
            || noteFilter.value.length > 0;
    }

    // Filteroptionen mappen
    const mapFilters = (): void => {
        klasseItems.value = mapFilterOptionsHelper(rows.value, 'klasse');
        fachItems.value = mapFilterOptionsHelper(rows.value, 'fach');
        kursItems.value = mapFilterOptionsHelper(rows.value, 'kurs');
        jahrgangItems.value = mapFilterOptionsHelper(rows.value, 'jahrgang');
        noteItems.value = mapFilterOptionsHelper(rows.value, 'note');
    };
</script>


<style scoped>

    /*.truncate {
        @apply truncate
    } */

    header {
        @apply flex flex-col gap-4 p-6
    }

    header #headline {
        @apply flex items-center justify-start gap-6
    }

    .content-area {
        @apply mx-4
    }

    .myToggles {
        @apply m-4
    }

</style>

