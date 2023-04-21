<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import { Head } from '@inertiajs/inertia-vue3'
    import {onMounted, reactive, computed, ref, watch, PropType} from 'vue'
    import { Leistung } from '../Interfaces/Leistung'
    import { Column } from '../Interfaces/Column'
    import { usePage } from '@inertiajs/inertia-vue3'
    import axios, { AxiosResponse } from 'axios'
    import MahnungIndicator from '../Components/MahnungIndicator.vue'
    import NoteInput from '../Components/NoteInput.vue'
    import FachbezogeneBemerkungenIndicator from '../Components/FachbezogeneBemerkungenIndicator.vue'

    import {
        baseColumns,
        fachbezogeneBemerkungenColumns,
        notenColumns,
        teilleistungenColumns,
        mahnungenColumns,
        fehlstundenColumns,
    } from '../Helpers/columns.helper'

    import {
        SvwsUiCheckbox,
        SvwsUiSelectInput,
        SvwsUiDataTable,
        SvwsUiTextInput,
        SvwsUiIcon,
        SvwsUiTooltip,
    } from '@svws-nrw/svws-ui'

    import FehlstundenInput from '../Components/FehlstundenInput.vue'
    import {Settings} from '../Interfaces/Settings'
    import MahnungIndicatorReadonly from '../Components/MahnungIndicatorReadonly.vue'
    import FachbezogeneBemerkungenIndicatorReadonly from '../Components/FachbezogeneBemerkungenIndicatorReadonly.vue'

    const title = 'Notenmanager - mein Unterricht'

    let state = reactive({
        leistungen: <Leistung[]> [],
    })

    let filterOptions = <any>reactive({
        'jahrgaenge': [],
        'klassen': [],
        'kurse': [],
        'noten': [],
        'faecher': [],
    })


    const getToggleValue = (column: string): boolean => usePage().props.value.settings.filters[column] == 1

    let toggles = <{
        teilleistungen: boolean,
        mahnungen: boolean,
        bemerkungen: boolean,
        fehlstunden: boolean
    }>reactive({
        teilleistungen: getToggleValue('mein_unterricht_teilleistungen'),
        mahnungen: getToggleValue('mein_unterricht_mahnungen'),
        bemerkungen: getToggleValue('mein_unterricht_bemerkungen'),
        fehlstunden: getToggleValue('mein_unterricht_fehlstunden'),
    })

    let props = defineProps({
        settings: {
            type: Object as PropType<Settings>,
            required: true,
        }
    })


    watch(toggles, (): void => drawTable())

    let filters = <{
        search: string,
        klasse: Number | string,
        jahrgang: Number | string,
        kurs: Number | string,
        fach: Number | string,
        note: Number | string,
    }>reactive({
        search: '',
        klasse: '0',
        jahrgang: 0,
        kurs: '0',
        fach: '0',
        note: 0,
    })

    const columns = ref<Column[]>([])

    const drawTable = (): void => {
        const pushTable = (pushable: boolean, array: Array<Column>): void => {
            if (pushable) array.forEach((column: Column): number => columns.value.push(column))
        }

        columns.value.length = 0

        pushTable(true, baseColumns)
        pushTable(toggles.teilleistungen, teilleistungenColumns)
        pushTable(true, notenColumns)
        pushTable(toggles.mahnungen, mahnungenColumns)
        pushTable(toggles.fehlstunden, fehlstundenColumns)
        pushTable(toggles.bemerkungen, fachbezogeneBemerkungenColumns)
    }

    onMounted((): void => {
        getLeistungen()
        drawTable()
    })

    const getFilters = (): void => {
        filterOptions.kurse = setFilters(state.leistungen, 'kurs', false)
        filterOptions.noten = setFilters(state.leistungen, 'note')
        filterOptions.jahrgaenge = setFilters(state.leistungen, 'jahrgang')
        filterOptions.klassen = setFilters(state.leistungen, 'klasse')
        filterOptions.faecher = setFilters(state.leistungen, 'fach')
    }

    const getLeistungen = (): Promise<any> => axios
        .get(route('api.mein_unterricht'))
        .then((response: AxiosResponse): void => state.leistungen = response.data)
        .finally(() => getFilters())

    const setFilters = (data, column: string, hasEmptyValue: boolean = true): {
        label: string, index: string | null | number
    }[] => {
        let hasEmpty: boolean = true

        let set = [
            ...new Set(data.map((item: any): string => item[column]))
        ]
        .filter((item: string): boolean => {
            if (['', null].includes(item)) {
                hasEmpty = hasEmptyValue
                return false
            }

            return true
        })
        .map((item: string): { label: string, index: string | null | number } => {
            return { label: item, index: item }
        })

        set.sort(function(a, b) {
            let textA = a.label.toUpperCase()
            let textB = b.label.toUpperCase()
            return (textA < textB) ? -1 : (textA > textB) ? 1 : 0
        })

        if (hasEmpty) {
            set.unshift({ label: 'Leer', index: '' })
        }
        set.unshift({ label: 'Alle', index: '0' })

        return set;
    }

    const filteredLeistungen = computed((): Array<Leistung> => state.leistungen
        .filter((leistung: Leistung): boolean =>
            searchFilter(leistung)
            && tableFilter(leistung, 'kurs')
            && tableFilter(leistung, 'klasse')
            && tableFilter(leistung, 'jahrgang')
            && tableFilter(leistung, 'note')
            && tableFilter(leistung, 'fach')
        )
    )

    const searchFilter = (leistung: Leistung): boolean => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(leistung.vorname) || search(leistung.nachname)
    }

    const tableFilter = (leistung: Leistung, column: string): boolean => {
        if (filters[column] == '0') return true
        return leistung[column] == filters[column]
    }

    const updateFachbezogeneBemerkungen = (fb: string, data: Leistung): string => data.fachbezogeneBemerkungen = fb
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
                <div id="toggles">
                    <SvwsUiCheckbox v-model="toggles.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.mahnungen" :value="true">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.fehlstunden" :value="true">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.bemerkungen" :value="true">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                </div>
                <div id="filters">
                    <SvwsUiTextInput type="search" placeholder="Suche" v-model="filters.search"></SvwsUiTextInput>
                    <SvwsUiSelectInput placeholder="Klasse" v-model="filters.klasse" :options="filterOptions.klassen"></SvwsUiSelectInput>
                    <SvwsUiSelectInput placeholder="Jahrgang" v-model="filters.jahrgang" :options="filterOptions.jahrgaenge"></SvwsUiSelectInput>
                    <SvwsUiSelectInput placeholder="Fach" v-model="filters.fach" :options="filterOptions.faecher"></SvwsUiSelectInput>
                    <SvwsUiSelectInput placeholder="Kurs" v-model="filters.kurs" :options="filterOptions.kurse"></SvwsUiSelectInput>
                    <SvwsUiSelectInput placeholder="Note" v-model="filters.note" :options="filterOptions.noten"></SvwsUiSelectInput>
                </div>
            </header>

            <h3 class="text-headline-sm ui-mx-6" v-if="filteredLeistungen.length === 0">Keine Einträge gefunden!</h3>

            <SvwsUiDataTable v-else :items="filteredLeistungen" :columns="columns" clickable>
                <template #header(istGemahnt)="{ column: { label } }">
                    <SvwsUiTooltip indicator="info">
                        M
                        <template #content>
                            Mahnungen
                        </template>
                    </SvwsUiTooltip>
                </template>
                <template #header(fs)="{ column: { label } }">
                    <SvwsUiTooltip indicator="info">
                        FS
                        <template #content>
                            Fachbezogene Fehlstunden
                        </template>
                    </SvwsUiTooltip>
                </template>
                <template #header(ufs)="{ column: { label } }">
                    <SvwsUiTooltip indicator="info">
                        FSU
                        <template #content>
                            Unentschuldigte fachbezogene Fehlstunden
                        </template>
                    </SvwsUiTooltip>
                </template>
                <template #header(fachbezogeneBemerkungen)="{ column: { label } }">
                    <SvwsUiTooltip indicator="info">
                        FB
                        <template #content>
                            Fachbezogene Bemerkungen
                        </template>
                    </SvwsUiTooltip>
                </template>



                <template #cell(note)="{ rowData }">
                    <div :class="{ readonly: !rowData.matrix.editable_noten }">
                        <NoteInput :leistung="rowData" :key="rowData.id" v-if="rowData.matrix.editable_noten"></NoteInput>
                        <strong v-else>
                            {{ rowData.note }}
                        </strong>
                    </div>
                </template>

                <template #cell(teilnoten)="{ rowData }">
                    <span class="readonly">TBD</span>
                </template>

                <template #cell(klasse)="{ rowData }">
                    <div class="readonly">
                        {{ rowData.klasse }}
                    </div>
                </template>

                <template #cell(kurs)="{ rowData }">
                    <div class="readonly">
                        {{ rowData.kurs }}
                    </div>
                </template>

                <template #cell(name)="{ rowData }">
                    <div class="readonly">
                        {{ rowData.name }}
                    </div>
                </template>

                <template #cell(fach)="{ rowData }">
                    <strong class="readonly">
                        {{ rowData.fach }}
                    </strong>
                </template>

                <template #cell(fs)="{ rowData }">
                    <div :class="{ readonly: !rowData.matrix.editable_fehlstunden }">
                        <FehlstundenInput :model="rowData" column="fs" v-if="rowData.matrix.editable_fehlstunden"></FehlstundenInput>
                        <strong v-else>
                            {{ rowData.fs }}
                        </strong>
                    </div>
                </template>

                <template #cell(ufs)="{ rowData }">
                    <div :class="{ readonly: !rowData.matrix.editable_fehlstunden }">
                        <FehlstundenInput :model="rowData" column="ufs" v-if="rowData.matrix.editable_fehlstunden"></FehlstundenInput>
                        <strong v-else>
                            {{ rowData.ufs }}
                        </strong>
                    </div>
                </template>

                <template #cell(istGemahnt)="{ rowData }">
                    <div :class="{ readonly: !rowData.matrix.editable_mahnungen }">
                        <MahnungIndicator :leistung="rowData" :key="rowData.id" :disabled="false" v-if="rowData.matrix.editable_mahnungen"></MahnungIndicator>
                        <MahnungIndicatorReadonly v-else :leistung="rowData" :key="rowData.id" :disabled="true"></MahnungIndicatorReadonly>
                    </div>
                </template>

                <template #cell(fachbezogeneBemerkungen)="{ rowData }">
                    <div :class="{ readonly: !rowData.matrix.editable_fb }">
                        <FachbezogeneBemerkungenIndicator :leistung="rowData" @updated="updateFachbezogeneBemerkungen($event, rowData)" v-if="rowData.matrix.editable_fb"></FachbezogeneBemerkungenIndicator>
                        <FachbezogeneBemerkungenIndicatorReadonly :leistung="rowData" @updated="updateFachbezogeneBemerkungen($event, rowData)" v-else></FachbezogeneBemerkungenIndicatorReadonly>
                    </div>
                </template>
            </SvwsUiDataTable>
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

    header #toggles {
        @apply ui-flex ui-items-center ui-justify-start ui-gap-3 ui-flex-wrap
    }

    header #headline {
        @apply ui-flex ui-items-center ui-justify-start ui-gap-6
    }

    header #filters {
        @apply ui-grid sm:ui-grid-cols-2 md:ui-grid-cols-3 lg:ui-grid-cols-6 ui-gap-6
    }
</style>
