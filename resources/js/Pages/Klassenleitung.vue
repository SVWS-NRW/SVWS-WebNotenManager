<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import {computed, onMounted, PropType, reactive, Ref, ref, watch} from 'vue'
    import axios, { AxiosPromise, AxiosResponse } from 'axios'
    import {Head, usePage} from '@inertiajs/inertia-vue3'
    import { Column } from '../Interfaces/Column'
    import { Schueler } from '../Interfaces/Schueler'
    import { Settings } from '../Interfaces/Settings'
    import BemerkungIndicator from '../Components/BemerkungIndicator.vue'
    import BemerkungenIndicatorReadonly from '../Components/BemerkungenIndicatorReadonly.vue'
    import FehlstundenInput from '../Components/FehlstundenInput.vue'

    import {
        SvwsUiCheckbox,
        SvwsUiSelectInput,
        SvwsUiDataTable,
        SvwsUiTextInput,
        SvwsUiIcon,
        SvwsUiContentCard,
        SvwsUiTooltip,
        SvwsUiButton,
    } from '@svws-nrw/svws-ui'
    import {Leistung} from '../Interfaces/Leistung'
    import BemerkungEditor from '../Components/BemerkungEditor.vue'
    import {Auth} from '../Interfaces/Auth'
    import {tableCellEditable} from '../Helpers/pages.helper'

    let props = defineProps({
        settings: {
            type: Object as PropType<Settings>,
            required: true,
        }
    })

    const title = 'Notenmanager - Klassenleitung'

    let state = reactive({
        schueler: <Schueler[]> [],
    })



    const auth: Auth = usePage().props.value.auth

    let filterOptions = <any>reactive({
        'klassen': [],
    })

    let filters = <{
        search: string,
        klasse: Number | string
    }>reactive({
        search: '',
        klasse: 0,
    })

    const columns = ref<Column[]>([])

    const drawTable = (): Column[] => columns.value = [
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, minWidth: 6, disabled: true  },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 10, disabled: true , },
        { key: 'gfs', label: 'GFS', sortable: true, span: 1, minWidth: 6, },
        { key: 'gfsu', label: 'GFSU', sortable: true, span: 1, minWidth: 6, },
        { key: 'ASV', label: 'ASV', sortable: true, span: 8, minWidth: 5, },
        { key: 'AUE', label: 'AUE', sortable: true, span: 8, minWidth: 5, },
        { key: 'ZB', label: 'ZB', sortable: true, span: 8, minWidth: 5, },
    ]

    onMounted((): void => {
        drawTable()
        fetchSchueler()
    })

    const getFilters = (): void => {
        filterOptions.klassen = setFilters(state.schueler, 'klasse')
    }

    const setFilters = (data, column: string): { label: string, index: string | null | number }[] => {
        let set = [
            ...new Set(data.map((item: any): string => item[column]))
        ].map((item: string): {
            label: string, index: string | null | number
        } => {
            return { label: item ?? 'Leer', index: item }
        })

        set.unshift({ label: 'Alle', index: '0' })

        return set
    }

    const filteredSchueler = computed((): Array<Schueler> =>
        state.schueler.filter((schueler: Schueler): boolean =>
            searchFilter(schueler) && tableFilter(schueler, 'klasse', true)
        )
    )

    const searchFilter = (schueler: Schueler): boolean => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(schueler.vorname) || search(schueler.nachname)
    }

    const tableFilter = (schueler: Schueler, column: string, withOnlyEmptyOption: boolean = false): boolean => {
        if (withOnlyEmptyOption && [null, ''].includes(filters[column])) return schueler[column] == null
        if (filters[column] == 0) return true
        return schueler[column] == filters[column]
    }

    const fetchSchueler = (): AxiosPromise => axios
        .get(route('api.klassenleitung'))
        .then((response: AxiosResponse): AxiosResponse => state.schueler = response.data)
        .finally((): void => getFilters())

    const selectedSchueler: Ref<Schueler | null> = ref(null)
    const selectedFloskelgruppe: Ref<string> = ref('asv')

    const selectSchueler = (schueler: Schueler, floskelgruppe?: string): void => {
        selectedSchueler.value = null
        selectedSchueler.value = schueler

        if (floskelgruppe) {
            selectedFloskelgruppe.value = floskelgruppe
        }
    }

    const valueReadonly = (schueler: Schueler, permission: 'editable_fb'): boolean => !schueler.matrix[permission]


    const editable = (condition: boolean): boolean => tableCellEditable(condition, auth.administrator) // ok
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <AppLayout title="Klassenleitung">
        <template v-slot:aside v-if="selectedSchueler">
            <BemerkungEditor
                :schueler="selectedSchueler"
                :floskelgruppe="selectedFloskelgruppe"
                @close="selectedSchueler = null"
                @updated="selectedSchueler[selectedFloskelgruppe.toUpperCase()] = $event; drawTable()"
            ></BemerkungEditor>
        </template>

        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">{{ title }}</h2>
                </div>
                <div id="filters">
                    <SvwsUiTextInput type="search" placeholder="Suche" v-model="filters.search"></SvwsUiTextInput>
                    <SvwsUiSelectInput placeholder="Klasse" v-model="filters.klasse" :options="filterOptions.klassen"></SvwsUiSelectInput>
                </div>
            </header>

            <SvwsUiDataTable v-if="filteredSchueler.length" :items="filteredSchueler" :columns="columns" clickable>
                <template #header(ASV)="{ column: { label } }">
                    <SvwsUiTooltip>
                        ASV
                        <template #content>
                            Arbeits- und Sozialverhalten
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #header(AUE)="{ column: { label } }">
                    <SvwsUiTooltip>
                        AUE
                        <template #content>
                            Außerunterrichtliches Engagement
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #header(ZB)="{ column: { label } }">
                    <SvwsUiTooltip>
                        ZB
                        <template #content>
                            Zeugnisbemerkung
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #header(gfs)="{ column: { label } }">
                    <SvwsUiTooltip>
                        GFS
                        <template #content>
                            Gesamtfehlstunden
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #header(gfsu)="{ column: { label } }">
                    <SvwsUiTooltip>
                        GFSU
                        <template #content>
                            Unentschuldigte Gesamtfehlstunden
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #cell(name)="{ rowData }">
                    <button @click="selectSchueler(rowData)" class="truncate">
                        {{ rowData.name }}
                    </button>
                </template>

                <template #cell(klasse)="{ rowData }">
                    <button @click="selectSchueler(rowData)"  class="truncate">
                        {{ rowData.klasse }}
                    </button>
                </template>

                <template #cell(gfs)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_fehlstunden && !rowData.matrix.toggleable_fehlstunden) }">
                        <FehlstundenInput :model="rowData" column="gfs" v-if="editable(rowData.matrix.editable_fehlstunden && !rowData.matrix.toggleable_fehlstunden)"></FehlstundenInput>
                        <strong v-else>
                            {{ rowData.gfs }}
                        </strong>
                    </div>
                </template>

                <template #cell(gfsu)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_fehlstunden && !rowData.matrix.toggleable_fehlstunden) }">
                        <FehlstundenInput :model="rowData" column="gfsu" v-if="editable(rowData.matrix.editable_fehlstunden && !rowData.matrix.toggleable_fehlstunden)"></FehlstundenInput>
                        <strong v-else>
                            {{ rowData.gfsu }}
                        </strong>
                    </div>
                </template>

                <template #cell(ASV)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_asv) }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['ASV']"
                            @clicked="selectSchueler(rowData, 'asv')"
                        ></BemerkungIndicator>
                   </div>
                </template>

                <template #cell(AUE)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_aue) }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['AUE']"
                            @clicked="selectSchueler(rowData, 'aue')"
                        ></BemerkungIndicator>
                   </div>
                </template>

                <template #cell(ZB)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_zb) }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData['ZB']"
                            @clicked="selectSchueler(rowData, 'zb')"
                        ></BemerkungIndicator>
                    </div>
                </template>
            </SvwsUiDataTable>

            <h3 class="text-headline-sm ui-mx-6" v-else>Keine Einträge gefunden!</h3>
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

    header #filters {
        @apply ui-grid sm:ui-grid-cols-2 md:ui-grid-cols-3 lg:ui-grid-cols-6 ui-gap-6
    }
</style>
