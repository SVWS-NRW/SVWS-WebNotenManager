<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import { computed, onMounted, PropType, reactive, ref } from 'vue'
    import axios, { AxiosPromise, AxiosResponse } from 'axios'
    import {Head, usePage} from '@inertiajs/inertia-vue3'
    import { Column } from '../Interfaces/Column'
    import { Schueler } from '../Interfaces/Schueler'
    import { Settings } from '../Interfaces/Settings'
    import BemerkungenIndicator from '../Components/BemerkungenIndicator.vue'
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
    } from '@svws-nrw/svws-ui'
    import {Leistung} from '../Interfaces/Leistung'

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


    const setupColumns = (): void => {
        columns.value.push(
            { key: 'klasse', label: 'Klasse', sortable: true },
            { key: 'name', label: 'Name, Vorname', sortable: true },
        )

        if (usePage().props.value.settings.general.klassenleitung_fehlstunden_visible) {
            columns.value.push(
                { key: 'gfs', label: 'gfs', sortable: true },
                { key: 'gfsu', label: 'gfsu', sortable: true },
            )
        }

        columns.value.push(
            { key: 'ASV', label: 'ASV', sortable: true },
            { key: 'AUE', label: 'AUE', sortable: true },
            { key: 'ZB', label: 'ZB', sortable: true },
        )
    }

    onMounted((): void => {
        setupColumns()
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
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <AppLayout title="">
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
                            Arbeits und Sozialverhalten
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
                        gfs
                        <template #content>
                            Gesamtfehlstunden
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #header(gfsu)="{ column: { label } }">
                    <SvwsUiTooltip>
                        gfsu
                        <template #content>
                            Unentschuldigte Gesamtfehlstunden
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #cell(name)="{ rowData }">
                    <span class="readonly">{{ rowData.name }}</span>
                </template>

                <template #cell(klasse)="{ rowData }">
                    <span class="readonly">{{ rowData.klasse }}</span>
                </template>

                <template #cell(gfs)="{ rowData }">
                    <div :class="{ readonly: rowData.matrix.editable_fehlstunden }">
                        <FehlstundenInput :model="rowData" column="gfs" v-if="!rowData.matrix.editable_fehlstunden"></FehlstundenInput>
                        <strong v-else>
                            {{ rowData.gfs }}
                        </strong>
                    </div>
                </template>

                <template #cell(gfsu)="{ rowData }">
                    <div :class="{ readonly: rowData.matrix.editable_fehlstunden }">
                        <FehlstundenInput :model="rowData" column="gfsu" v-if="!rowData.matrix.editable_fehlstunden"></FehlstundenInput>
                        <strong v-else>
                            {{ rowData.gfsu }}
                        </strong>
                    </div>
                </template>

                <template #cell(ASV)="{ rowData }">
                    <div :class="{ readonly: !rowData.matrix.editable_asv }">
                        <BemerkungenIndicator :leistung="rowData" floskelgruppe="ASV" v-if="rowData.matrix.editable_asv"></BemerkungenIndicator>
                        <BemerkungenIndicatorReadonly :leistung="rowData" floskelgruppe="ASV" v-else></BemerkungenIndicatorReadonly>
                   </div>
                </template>

                <template #cell(AUE)="{ rowData }">
                    <div :class="{ readonly: !rowData.matrix.editable_aue }">
                        <BemerkungenIndicator :leistung="rowData" floskelgruppe="AUE" v-if="rowData.matrix.editable_aue"></BemerkungenIndicator>
                        <BemerkungenIndicatorReadonly :leistung="rowData" floskelgruppe="AUE" v-else></BemerkungenIndicatorReadonly>
                   </div>
                </template>

                <template #cell(ZB)="{ rowData }">
                    <div :class="{ readonly: !rowData.matrix.editable_zb }">
                        <BemerkungenIndicator :leistung="rowData" floskelgruppe="ZB" v-if="rowData.matrix.editable_zb"></BemerkungenIndicator>
                        <BemerkungenIndicatorReadonly :leistung="rowData" floskelgruppe="ZB" v-else></BemerkungenIndicatorReadonly>
                    </div>
                </template>
            </SvwsUiDataTable>

            <h3 class="text-headline-sm ui-mx-6" v-else>Keine Einträge gefunden!</h3>
        </template>
    </AppLayout>
</template>

<style scoped>
    .readonly {
        @apply ui-bg-gray-200 ui-w-full ui-block ui-h-full
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
