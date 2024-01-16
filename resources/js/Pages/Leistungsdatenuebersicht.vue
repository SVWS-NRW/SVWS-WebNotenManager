<template>
    <Head>
        <title>Notenmanager - Leistungsdatenübersicht</title>
    </Head>

    <AppLayout>
         <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">Notenmanager - Leistungsdatenübersicht</h2>
                </div>
            </header>
            <div class="content-area">
                <SvwsUiTable
                    :items="rowsFiltered"
                    :columns="cols"
                    :clickable="true"
                    :count="true"
                    :filtered="isFiltered()"
                    :filterReset="filterReset"  
                    :filterOpen="true"
                    :sortByAndOrder="{ key: 'klasse', order: true}"
                >
                    <template #filter>
                        <div class="edition-pencil-button">
                            <SvwsUiButton
                                @click="leistungEditableToggle()"
                                v-if="lehrerCanOverrideFachlehrer"
                                :type="leistungEditable ? 'primary' : 'secondary'"
                                size="big"
                            >
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
                        <SvwsUiButton class="export-button" type="transparent" @click="exportToFile('csv')">CSV</SvwsUiButton>
                        <SvwsUiButton class="export-button" type="transparent" @click="exportToFile('excel')">Excel</SvwsUiButton>
                        
                    </template>
                    <template #filterAdvanced>
                        <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
                        <SvwsUiMultiSelect
                            label="Klasse"
                            :items="klasseItems"
                            :item-text="item => item"
                            v-model="klasseFilter"
                        />
                        <SvwsUiMultiSelect
                            label="Jahrgang"
                            :items="jahrgangItems"
                            :item-text="item => item"
                            v-model="jahrgangFilter"
                        />
                        <SvwsUiMultiSelect
                            label="Fach"
                            :items="fachItems"
                            :item-text="item => item"
                            v-model="fachFilter"
                        />
                        <SvwsUiMultiSelect
                            label="Kurs"
                            :items="kursItems"
                            :item-text="item => item"
                            v-model="kursFilter"
                        />
                        <SvwsUiMultiSelect
                            label="Note"
                            :items="noteItems"
                            :item-text="item => item"
                            v-model="noteFilter"
                        />
                    </template>

                    <template #cell(klasse)="{ value, rowData }">
                        <BemerkungButton
                            :value="value"
                            :model="rowData"
                            floskelgruppe="fb"
                            @clicked="selectLeistung(rowData)"
                        />
                    </template>

                    <template #cell(name)="{ value, rowData }">
                        <BemerkungButton
                            :value="value"
                            :model="rowData"
                            floskelgruppe="fb"
                            @clicked="selectLeistung(rowData)"
                        />
                    </template>

                    <template #cell(fach)="{ value, rowData }">
                        <BemerkungButton
                            :value="value"
                            :model="rowData"
                            floskelgruppe="fb"
                            @clicked="selectLeistung(rowData)"
                        />
                    </template>

                    <template #cell(kurs)="{ value, rowData }">
                        <BemerkungButton
                            :value="value"
                            :model="rowData"
                            floskelgruppe="fb"
                            @clicked="selectLeistung(rowData)"
                        />
                    </template>

                    <template #cell(lehrer)="{ value, rowData }">
                        <BemerkungButton
                            :value="value"
                            :model="rowData"
                            floskelgruppe="fb"
                            @clicked="selectLeistung(rowData)"
                        />
                    </template>

                    <template #cell(note)="{ value, rowData }">
                        <NoteInput
                            :leistung="rowData"
                            :disabled="inputDisabled(rowData.editable.noten)"
                        />
                    </template>

                    <template #cell(istGemahnt)="{ value, rowData }">
                        <MahnungIndicator
                            :leistung="rowData"
                            :disabled="inputDisabled(rowData.editable.mahnungen)"
                        />
                    </template>

                    <template #cell(fs)="{ value, rowData }">
                        <FehlstundenInput
                            column="fs"
                            :model="rowData"
                            :disabled="inputDisabled(rowData.editable.fehlstunden)"
                        />
                    </template>

                    <template #cell(fsu)="{ value, rowData }">
                        <FehlstundenInput
                            column="fsu"
                            :model="rowData"
                            :disabled="inputDisabled(rowData.editable.fehlstunden)"
                        />
                    </template>

                    <template #cell(fachbezogeneBemerkungen)="{ value, rowData }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData.fachbezogeneBemerkungen"
                            @clicked="selectLeistung(rowData, true)"
                            floskelgruppe="fb"
                        />
                    </template>
                </SvwsUiTable>
            </div>
        </template>

        <template v-slot:aside v-if="selectedLeistung">
            <FbEditor
                :leistung="selectedLeistung"
                @updated="selectedLeistung.fachbezogeneBemerkungen = $event;"
                @close="selectedLeistung = null"
                :editable="leistungEditable"
            ></FbEditor>
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
    import {
        DataTableColumn, SvwsUiTable, SvwsUiCheckbox, SvwsUiTextInput, SvwsUiMultiSelect, SvwsUiButton,
    } from '@svws-nrw/svws-ui';
    import {
        BemerkungButton, BemerkungIndicator, FbEditor, FehlstundenInput, MahnungIndicator, NoteInput,
    } from '@/Components/Components';
    import se from "../../../public/build/assets/Datenschutz-b9850b5d";

    const rows: Ref<Leistung[]> = ref([]);

    const rowsFiltered = computed((): Leistung[] => {
        return rows.value.filter((leistung: Leistung): boolean => {
            return searchHelper(leistung, ['name'], searchFilter.value)
                && multiSelectHelper(leistung, 'klasse', klasseFilter.value)
                && multiSelectHelper(leistung, 'fach', fachFilter.value)
                && multiSelectHelper(leistung, 'kurs', kursFilter.value)
                && multiSelectHelper(leistung, 'jahrgang', jahrgangFilter.value)
                && multiSelectHelper(leistung, 'note', noteFilter.value)
        });
    });

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

    let lehrerCanOverrideFachlehrer = ref(false);
    let leistungEditable: Ref<boolean> = ref(false);
    const leistungEditableToggle = (): void => {
        if (true === lehrerCanOverrideFachlehrer.value) {
            leistungEditable.value = !leistungEditable.value;
        }
    };

    const inputDisabled = (condition: boolean): boolean => !(condition && leistungEditable.value);

    onMounted((): AxiosPromise => axios
        .get(route('api.leistungsdatenuebersicht'))
        .then((response: AxiosResponse): void => {
            rows.value = response.data.data;
            toggles.value = response.data.toggles;
            lehrerCanOverrideFachlehrer.value = response.data.lehrerCanOverrideFachlehrer;
        })
        .finally((): void => mapFilters())
    );

    const cols = computed((): DataTableColumn[] => [
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, minWidth: 6, disabled: false },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 10, disabled: false },
        ...(toggles.value.fach ? [
            { key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, disabled: false },
        ] : []),
        ...(toggles.value.kurs ? [
            { key: 'kurs', label: 'Kurs', sortable: true, span: 2, minWidth: 5, disabled: false },
        ] : []),
        ...(toggles.value.fachlehrer ? [
            { key: 'lehrer', label: 'Fachlehrer', sortable: true, span: 2, minWidth: 7 }
        ] : []),
        ...(toggles.value.teilleistungen ? [
            { key: 'teilnoten', label: 'Teilnoten', sortable: true, span: 5, minWidth: 6 }
        ] : []),
        ...(toggles.value.note ? [
            { key: 'note', label: 'Note', sortable: true, span: 1, minWidth: 6 },
        ] : []),
            ...(toggles.value.mahnungen ? [
            { key: 'istGemahnt', label: 'Mahnungen', sortable: true, span: 1, minWidth: 8},
        ] : []),
        ...(toggles.value.fehlstunden ? [
            { key: 'fs', label: 'FS', sortable: true, span: 1, minWidth: 6 },
            { key: 'fsu', label: 'FSU', sortable: true, span: 1, minWidth: 6 },
        ] : []),
        ...(toggles.value.bemerkungen ? [
            { key: 'fachbezogeneBemerkungen', label: 'FB', sortable: true, span: 12, minWidth: 4 },
        ] : []),
    ])

    const selectedLeistung: Ref<Leistung | null> = ref(null);

    const selectLeistung = (leistung: Leistung, always: boolean = false): Leistung | null => {
        selectedLeistung.value = (selectedLeistung.value || always) ? leistung : null;
        return selectedLeistung;
    }

    // Filters
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

    const filterReset = (): void => {
        searchFilter.value = '';
        klasseFilter.value = [];
        jahrgangFilter.value = [];
        fachFilter.value = [];
        kursFilter.value = [];
        noteFilter.value = [];
    };

    const isFiltered = (): boolean => {
        return searchFilter.value !== null
            || klasseFilter.value.length > 0
            || jahrgangFilter.value.length > 0
            || fachFilter.value.length > 0
            || kursFilter.value.length > 0
            || noteFilter.value.length > 0;
    }

    const mapFilters = (): void => {
        klasseItems.value = mapFilterOptionsHelper(rows.value, 'klasse');
        fachItems.value = mapFilterOptionsHelper(rows.value, 'fach');
        kursItems.value = mapFilterOptionsHelper(rows.value, 'kurs');
        jahrgangItems.value = mapFilterOptionsHelper(rows.value, 'jahrgang');
        noteItems.value = mapFilterOptionsHelper(rows.value, 'note');
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
        @apply mx-4
    }

    .export-button {
        @apply ml-6
    }
</style>