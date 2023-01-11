<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import {computed, ref} from 'vue'
    import { Head } from '@inertiajs/inertia-vue3'
    import {onMounted, reactive} from 'vue'
    import { Leistung } from '../Interfaces/Leistung'
    import { Filter, LeistungsDatenFilterValues } from '../Interfaces/Filter'
    import axios, {AxiosPromise, AxiosResponse} from 'axios'
    import {SvwsUiTable} from '@svws-nrw/svws-ui'

    import { SvwsUiTextInput, SvwsUiSelectInput, SvwsUiCheckbox } from '@svws-nrw/svws-ui'
    import { Column } from '../Interfaces/Column'

    let title = 'Notenmanager - Mein Unterricht'

    let state = reactive({
        // selected: <Leistung | null> null,
        leistungen: <Leistung[]> [],
    })

    let filterOptions = <LeistungsDatenFilterValues>reactive({
        'jahrgaenge': [],
        'klassen': [],
        'kurse': [],
        'noten': [],
        'faecher': [],
    },)

    let toggles = reactive({
        teilleistungen: false,
        mahnungen: true,
        bemerkungen: true,
        fehlstunden: false,
    })

    const filters = reactive({
        search: <string> '',
        klasse: <Number | string> 0,
        jahrgang: <Number | string> 0,
        kurs: <Number | string> 0,
        fach: <Number | string> '0',
        note: <Number | string> 0,
    })

    const columns = ref<Column[]>([
        { key: 'klasse', label: 'Klasse', sortable: true },
        { key: 'name', label: 'Name', sortable: true },
        { key: 'fach', label: 'Fach', sortable: true },
        { key: 'lehrer', label: 'Lehrer', sortable: true },
        { key: 'kurs', label: 'Kurs', sortable: true },
        { key: 'note', label: 'Note', sortable: false },
    ]);

    onMounted((): void => {
        getFilters()
        getLeistungen()
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
    );

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
                    <h2 class="headline-2">{{ title }}</h2>
                </div>
                <div id="toggles">
                    <SvwsUiCheckbox v-model="toggles.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.mahnungen" :value="true">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.bemerkungen" :value="true">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.fehlstunden" :value="true">Fachbezogene Fehlstunden</SvwsUiCheckbox>
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
            <SvwsUiTable :data="filteredLeistungen" :columns="columns">
                <template #cell-name="{ row }">
<!--                    {{ row.nachname }}, {{ row.vorname }}-->
                </template>
            </SvwsUiTable>
        </template>
    </AppLayout>
</template>

<style>
    header {
        @apply flex flex-col gap-4 p-6
    }

    header #toggles {
        @apply flex items-center justify-start gap-3 flex-wrap
    }

    header #headline {
        @apply flex items-center justify-start gap-6
    }

    header #filters {
        @apply grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6
    }
</style>
