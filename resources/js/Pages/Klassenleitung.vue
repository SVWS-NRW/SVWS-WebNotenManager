<script setup lang="ts">
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosPromise, AxiosResponse } from 'axios'
    import { computed, onMounted, Ref, ref } from 'vue'
    import { mapFilterOptionsHelper, multiSelectHelper, searchHelper } from '@/Helpers/tableHelper'
    import { DataTableColumn, SvwsUiTable, SvwsUiMultiSelect, SvwsUiTextInput } from '@svws-nrw/svws-ui'
    import { Schueler } from '@/Interfaces/Interface'
    import { BemerkungIndicator, FehlstundenInput, BemerkungButton, BemerkungEditor } from '@/Components/Components'

    const title = 'Notenmanager - Klassenleitung'

    const rows: Ref<Schueler[]> = ref([])

    const rowsFiltered = computed((): Schueler[] =>
        rows.value.filter((schueler: Schueler): boolean =>
            searchHelper(schueler, ['name'], searchFilter.value)
            && multiSelectHelper(schueler, 'klasse', klasseFilter.value)
        )
    )

    onMounted((): AxiosPromise => axios
        .get(route('api.klassenleitung'))
        .then((response: AxiosResponse): AxiosResponse => rows.value = response.data)
        .finally((): string[] => klasseItems.value = mapFilterOptionsHelper(rows.value, 'klasse'))
    )

    const cols: Ref<DataTableColumn[]> = ref([
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, minWidth: 6, },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 10, },
        { key: 'gfs', label: 'GFS', sortable: true, span: 10, minWidth: 6, },
        { key: 'gfsu', label: 'GFSU', sortable: true, span: 10, minWidth: 6, },
        { key: 'asv', label: 'ASV', sortable: true, span: 8, minWidth: 5, },
        { key: 'aue', label: 'AUE', sortable: true, span: 8, minWidth: 5, },
        { key: 'zb', label: 'ZB', sortable: true, span: 8, minWidth: 5, },
    ])

    const selectedSchueler: Ref<Schueler|null> = ref(null)
    const selectedFloskelgruppe: Ref<string> = ref('asv')

    const selectSchueler = (schueler: Schueler, floskelgruppe: string): void => {
        if (floskelgruppe || selectedSchueler.value != null) {
            selectedSchueler.value = schueler

            if (floskelgruppe) {
                selectedFloskelgruppe.value = floskelgruppe
            }
        }
    }

    const searchFilter: Ref<string|null> = ref(null)
    const klasseFilter: Ref <string[]> = ref([])
    const klasseItems: Ref<string[]> = ref([])

    const filterReset = (): void => {
        klasseFilter.value = []
        searchFilter.value = ""
    }

    const isFiltered = (): boolean => klasseFilter.value.length > 0 || searchFilter.value !== null

</script>

<template>
    <AppLayout title="Klassenleitung">
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
                    <template #filterAdvanced>
                        <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
                        <SvwsUiMultiSelect
                            label="Klasse"
                            :items="klasseItems"
                            :item-text="item => item"
                            v-model="klasseFilter"
                        />
                    </template>
                    <template #cell(klasse)="{ value, rowData }">
                        <BemerkungButton
                            :value="value"
                            :model="rowData"
                            :floskelgruppe="selectedFloskelgruppe"
                            @clicked="selectSchueler(rowData)"
                        />
                    </template>
                    <template #cell(name)="{ value, rowData }">
                        <BemerkungButton
                            @clicked="selectSchueler(rowData)"
                            :value="value"
                            :model="rowData"
                            :floskelgruppe="selectedFloskelgruppe"
                        />
                    </template>
                    <template #cell(gfs)="{ value, rowData }">
                        <FehlstundenInput column="gfs" :model="rowData" :disabled="!rowData.editable.fehlstunden" />
                    </template>
                    <template #cell(gfsu)="{ value, rowData }">
                        <FehlstundenInput column="gfsu" :model="rowData" :disabled="!rowData.editable.fehlstunden" />
                    </template>
                    <template #cell(asv)="{ value, rowData }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['ASV']"
                            @clicked="selectSchueler(rowData, 'asv')"
                            floskelgruppe="asv"
                        />
                    </template>
                    <template #cell(aue)="{ value, rowData }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['AUE']"
                            @clicked="selectSchueler(rowData, 'aue')"
                            floskelgruppe="aue"
                        />
                    </template>
                    <template #cell(zb)="{ value, rowData }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['ZB']"
                            @clicked="selectSchueler(rowData, 'zb')"
                            floskelgruppe="zb"
                        />
                    </template>
                </SvwsUiTable>
            </div>
        </template>

        <template v-slot:aside v-if="selectedSchueler">
            <BemerkungEditor
                :schueler="selectedSchueler"
                :floskelgruppe="selectedFloskelgruppe"
                :bemerkung="selectedSchueler[selectedFloskelgruppe.toUpperCase()]"
                @updated="selectedSchueler[selectedFloskelgruppe.toUpperCase()] = $event;"
                @close="selectedSchueler = null"
            ></BemerkungEditor>
        </template>
    </AppLayout>
</template>

<style scoped>
    header {
        @apply ui-flex ui-flex-col ui-gap-4 ui-p-6
    }

    .content-area {
        @apply ui-mx-4
    }
</style>

