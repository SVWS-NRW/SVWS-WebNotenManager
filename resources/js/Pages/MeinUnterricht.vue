<script setup lang="ts">
    import { computed, onMounted, Ref, ref } from 'vue'
    import axios, { AxiosPromise, AxiosResponse } from 'axios'
    import { Head } from '@inertiajs/inertia-vue3'
    import { Leistung, TableColumnToggle } from '@/Interfaces/Interface'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import { mapFilterOptionsHelper, multiSelectHelper, searchHelper } from '@/Helpers/tableHelper'
    import { DataTableColumn, SvwsUiTable, SvwsUiCheckbox, SvwsUiTextInput, SvwsUiMultiSelect } from '@svws-nrw/svws-ui'

    import {
        BemerkungIndicator, MahnungIndicator, NoteInput, FehlstundenInput, FbEditor, BemerkungButton
    } from '@/Components/Components'

    const title = 'Notenmanager - mein Unterricht'

    const rows: Ref<Leistung[]> = ref([])

    const rowsFiltered = computed((): Leistung[] =>
        rows.value.filter((leistung: Leistung): boolean =>
            searchHelper(leistung, ['name'], searchFilter.value)
            && multiSelectHelper(leistung, 'klasse', klasseFilter.value)
            && multiSelectHelper(leistung, 'fach', fachFilter.value)
            && multiSelectHelper(leistung, 'kurs', kursFilter.value)
            && multiSelectHelper(leistung, 'jahrgang', jahrgangFilter.value)
            && multiSelectHelper(leistung, 'note', noteFilter.value)
        )
    )

    const toggles: Ref<TableColumnToggle> = ref({
        teilleistungen: false,
        mahnungen: false,
        bemerkungen: false,
        fehlstunden: false,
        kurs: false,
        note: false,
        fach: false,
    })

    onMounted((): AxiosPromise => axios
        .get(route('api.mein_unterricht'))
        .then((response: AxiosResponse): AxiosResponse => {
            rows.value = response.data.data
            toggles.value = response.data.toggles
        })
        .finally((): void => mapFilters())
    )

    const cols = computed((): DataTableColumn[] => [
         { key: 'klasse', label: 'Klasse', sortable: true, span: 1, minWidth: 6, disabled: false },
         { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 10, disabled: false },
        ...(toggles.value.fach ? [
            { key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, disabled: false },
        ] : []),
        ...(toggles.value.kurs ? [
            { key: 'kurs', label: 'Kurs', sortable: true, span: 2, minWidth: 5, disabled: false },
        ] : []),
        ...(toggles.value.teilleistungen ? [
            { key: 'teilnoten', label: 'Teilnoten', sortable: true, span: 5, minWidth: 15 }
        ] : []),
        ...(toggles.value.note ? [
            { key: 'note', label: 'Note', sortable: true, span: 1, minWidth: 5 },
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

    const selectedLeistung: Ref<Leistung | null> = ref(null)

    const selectLeistung = (leistung: Leistung, always: boolean = false): Leistung | null =>
        selectedLeistung.value = (selectedLeistung.value || always) ? leistung : null

    // Filters
    const searchFilter: Ref<string|null> = ref(null)
    const klasseFilter: Ref <string[]> = ref([])
    const fachFilter: Ref <string[]> = ref([])
    const kursFilter: Ref <string[]> = ref([])
    const jahrgangFilter: Ref <string[]> = ref([])
    const noteFilter: Ref <string[]> = ref([])

    const klasseItems: Ref<string[]> = ref([])
    const fachItems: Ref<string[]> = ref([])
    const kursItems: Ref<string[]> = ref([])
    const jahrgangItems: Ref<string[]> = ref([])
    const noteItems: Ref<string[]> = ref([])

    const filterReset = (): void => {
        searchFilter.value = ''
        klasseFilter.value = []
        jahrgangFilter.value = []
        fachFilter.value = []
        kursFilter.value = []
        noteFilter.value = []
    }

    const isFiltered = (): boolean =>
        searchFilter.value !== null
        || klasseFilter.value.length > 0
        || jahrgangFilter.value.length > 0
        || fachFilter.value.length > 0
        || kursFilter.value.length > 0
        || noteFilter.value.length > 0

    const mapFilters = (): void => {
        klasseItems.value = mapFilterOptionsHelper(rows.value, 'klasse')
        fachItems.value = mapFilterOptionsHelper(rows.value, 'fach')
        kursItems.value = mapFilterOptionsHelper(rows.value, 'kurs')
        jahrgangItems.value = mapFilterOptionsHelper(rows.value, 'jahrgang')
        noteItems.value = mapFilterOptionsHelper(rows.value, 'note')
    }
</script>

<template>
    <Head>
        <title>Mein Unterricht</title>
    </Head>
    <AppLayout>
        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">{{ title }}</h2>
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
                >
                    <template #filter>
                        <SvwsUiCheckbox v-model="toggles.fach" :value="true">Fach</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.kurs" :value="true">Kurs</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.note" :value="true">Note</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.mahnungen" :value="true">Mahnungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.fehlstunden" :value="true">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="toggles.bemerkungen" :value="true">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                    </template>
                    <template #filterAdvanced>
                        <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
                        <SvwsUiMultiSelect label="Klasse"
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

                    <template #cell(note)="{ value, rowData }">
                        <NoteInput
                            :leistung="rowData"
                            :disabled="!rowData.editable.noten"
                        />
                    </template>

                    <template #cell(istGemahnt)="{ value, rowData }">
                        <MahnungIndicator :leistung="rowData" :disabled="!rowData.editable.mahnungen" />
                    </template>

                    <template #cell(fs)="{ value, rowData }">
                        <FehlstundenInput column="fs" :model="rowData" :disabled="!rowData.editable.fehlstunden"/>
                    </template>

                    <template #cell(fsu)="{ value, rowData }">
                        <FehlstundenInput column="fsu" :model="rowData" :disabled="!rowData.editable.fehlstunden"/>
                    </template>

                    <template #cell(fachbezogeneBemerkungen)="{ value, rowData }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['fachbezogeneBemerkungen']"
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
                :editable="true"
            ></FbEditor>
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

    header #headline {
        @apply ui-flex ui-items-center ui-justify-start ui-gap-6
    }

    .content-area {
        @apply ui-mx-4
    }

    .myToggles {
        @apply ui-m-4
    }
</style>
