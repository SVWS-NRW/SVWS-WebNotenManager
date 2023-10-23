<script setup lang="ts">
    import AppLayout from '@/Layouts/AppLayout.vue'
    import { DataTableColumn, SvwsUiTable, SvwsUiMultiSelect, SvwsUiTextInput } from '@svws-nrw/svws-ui'
    import axios, { AxiosPromise, AxiosResponse } from 'axios'
    import { computed, onMounted, Ref, ref } from 'vue'
    import { tableCellDisabled } from '@/Helpers/pages.helper'
    import { usePage } from '@inertiajs/inertia-vue3'
    import FehlstundenInput from '@/Components/FehlstundenInput.vue'
    import BemerkungIndicator from '@/Components/BemerkungIndicator.vue'
    import { Schueler } from '@/Interfaces/Schueler'
    import BemerkungEditor from '@/Components/BemerkungEditor.vue'

    const title = 'Notenmanager - Klassenleitung'

    const auth: any = usePage().props.value.auth
    const rows: Ref<Schueler[]> = ref([])

    const selectedSchueler: Ref<Schueler | null> = ref(null)
    const selectedFloskelgruppe: Ref<string> = ref('asv')
    const floskelgruppen: any = {
        'asv': 'Arbeits- und Sozialverhalten',
        'aue': 'Außerunterrichtliches Engagement',
        'zb': 'Zeugnisbemerkung',
    }

    const columns = ref([
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, minWidth: 6, },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 10, },
        { key: 'gfs', label: 'GFS', sortable: true, span: 1, minWidth: 6, },
        { key: 'gfsu', label: 'GFSU', sortable: true, span: 1, minWidth: 6, },
        { key: 'asv', label: 'ASV', sortable: true, span: 8, minWidth: 5, },
        { key: 'aue', label: 'AUE', sortable: true, span: 8, minWidth: 5, },
        { key: 'zb', label: 'ZB', sortable: true, span: 8, minWidth: 5, },
    ]) as Ref<DataTableColumn[]>
    const klasseFilter: Ref <string[]> = ref([])
    const searchFilter: Ref<string|null> = ref(null)
    const klasseItems: Ref<string[]> = ref([]);

    onMounted((): AxiosPromise => axios
        .get(route('api.klassenleitung'))
        .then((response: AxiosResponse): AxiosResponse => rows.value = response.data)
        .finally((): string[] => klasseItems.value = mapKlassen())
    )

    const mapKlassen = (): string[] => rows.value
        .map((schueler: Schueler): string => schueler.klasse)
        .filter((value: string, index:number, self: string[]): boolean => self.indexOf(value) === index)

    const selectSchueler = (schueler: Schueler, floskelgruppe: 'asv'|'aue'|'zb'|null = null): void => {
        if (floskelgruppe || selectedSchueler.value != null) {
            selectedSchueler.value = schueler

            if (floskelgruppe) {
                selectedFloskelgruppe.value = floskelgruppe
            }
        }
    }

    const inputDisabled = (condition: boolean): boolean => tableCellDisabled(condition, auth.administrator)

    const bemerkungButtonAriaLabel = (schueler: Schueler): string =>
        `Wechseln zu ${floskelgruppen[selectedFloskelgruppe.value]} für ${schueler.vorname} ${schueler.nachname}`

    const fehlstundenDisabled = (rowData: any): boolean =>
        rowData.matrix.editable_fehlstunden && !rowData.matrix.toggleable_fehlstunden

    const search = (schueler: Schueler, column: 'nachname'|'vorname'|'klasse'): boolean =>
        schueler[column].toLocaleLowerCase().includes(searchFilter.value?.toLocaleLowerCase() ?? '')

    const rowsFiltered = computed(() =>
        rows.value.filter((schueler: Schueler): boolean => {
            if (klasseFilter.value.length > 0) {
                return klasseFilter.value.includes(schueler.klasse)
            }

            if (searchFilter.value !== null) {
                return search(schueler, 'nachname')
                    || search(schueler, 'vorname')
                    || search(schueler, 'klasse')
            }

            return true
        })
    )

    const filterReset = (): void => {
        console.log("resetfilter")
        klasseFilter.value = []
        searchFilter.value = ""
    }

    const filtered = (): boolean => klasseFilter.value.length > 0 || searchFilter.value !== null

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
                    :items="rowsFiltered.values()"
                    :columns="columns"
                    :clickable="true"
                    :count="true"
                    :filtered="filtered()"
                    :filterReset="filterReset"
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

        <template v-slot:aside v-if="selectedSchueler">
            <BemerkungEditor
                :schueler="selectedSchueler"
                :floskelgruppe="selectedFloskelgruppe"
                @close="selectedSchueler = null"
                @updated="selectedSchueler[selectedFloskelgruppe.toUpperCase()] = $event;"
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

