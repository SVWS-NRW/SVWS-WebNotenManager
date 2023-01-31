<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import { Head } from '@inertiajs/inertia-vue3'
    import { onMounted, reactive, computed, ref, watch } from 'vue'
    import { Leistung } from '../Interfaces/Leistung'
    import { Column } from '../Interfaces/Column'
    import { LeistungsDatenFilterValues } from '../Interfaces/Filter'
    import axios, {AxiosPromise, AxiosResponse} from 'axios'
    import MahnungIndicator from '../Components/MahnungIndicator.vue'
    import NoteInput from '../Components/Dashboard/NoteInput.vue'

    import {
        baseColumns,
        fachbezogeneBemerkungenColumns,
        notenColumns,
        mahnungenColumns,
        fehlstundenColumns
    } from '../Helpers/columns.helper'

    import {
        SvwsUiCheckbox,
        SvwsUiSelectInput,
        SvwsUiTable,
        SvwsUiTextInput,
        SvwsUiIcon,
    } from '@svws-nrw/svws-ui'

    const title = 'Notenmanager - mein Unterricht'

    let state = reactive({
        leistungen: <Leistung[]> [],
    })

    let filterOptions = <LeistungsDatenFilterValues>reactive({
        'jahrgaenge': [],
        'klassen': [],
        'kurse': [],
        'noten': [],
        'faecher': [],
    })

    let toggles = <{
        teilleistungen: boolean,
        mahnungen: boolean,
        bemerkungen: boolean,
        fehlstunden: boolean
    }>reactive({
        teilleistungen: false,
        mahnungen: true,
        bemerkungen: true,
        fehlstunden: false,
    })

    watch(toggles, (): void => drawTable());

    let filters = <{
        search: string,
        klasse: Number | string,
        jahrgang: Number | string,
        kurs: Number | string,
        fach: Number | string,
        note: Number | string,
    }>reactive({
        search: '',
        klasse: 0,
        jahrgang: 0,
        kurs: 0,
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
        pushTable(true, notenColumns)
        pushTable(toggles.mahnungen, mahnungenColumns)
        pushTable(toggles.fehlstunden, fehlstundenColumns)
        pushTable(toggles.bemerkungen, fachbezogeneBemerkungenColumns)
    }

    onMounted((): void => {
        getFilters()
        getLeistungen()
        drawTable()
    })

    const getFilters = (): AxiosPromise => axios
        .get(route('get_filters'))
        .then((response: AxiosResponse): AxiosResponse => filterOptions = response.data)

    const getLeistungen = (): AxiosPromise => axios
        .get(route('get_leistungen'))
        .then((response: AxiosResponse): AxiosResponse => state.leistungen = mapLeistungen(response.data))

    const mapLeistungen = (data) => data.map((leistung: Leistung): Leistung => {
        leistung.name = `${leistung.nachname}, ${leistung.vorname}`
        return leistung
    })

    const filteredLeistungen = computed((): Array<Leistung> => state.leistungen
        .filter((leistung: Leistung): boolean =>
            searchFilter(leistung)
            && tableFilter(leistung, 'klasse', true)
            && tableFilter(leistung, 'kurs', true)
            && tableFilter(leistung, 'jahrgang')
            && tableFilter(leistung, 'note', true)
            && tableFilter(leistung, 'fach')
        )
    )

    const searchFilter = (leistung: Leistung): boolean => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(leistung.vorname) || search(leistung.nachname)
    }

    const tableFilter = (leistung: Leistung, column: string, containsOnlyEmptyOption: boolean = false): boolean => {
        if (containsOnlyEmptyOption && [null, ''].includes(filters[column])) return leistung[column] == null
        if (filters[column] == 0) return true
        return leistung[column] == filters[column]
    }
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <AppLayout>
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

            <SvwsUiTable v-else :data="filteredLeistungen" :columns="columns">
                <template #cell-note="{ row }">
                    <NoteInput :leistung="row"></NoteInput>
                </template>
                <template #cell-mahnung="{ row }">
                    <MahnungIndicator :leistung="row" :key="row.id" :disabled="false"></MahnungIndicator>
                </template>
                <template #cell-fachbezogeneBemerkungen="{ row }">
                    {{ row.fachbezogeneBemerkungen ? '[V]' : '[  ]' }}
                </template>
            </SvwsUiTable>
        </template>
    </AppLayout>
</template>

<style>
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
