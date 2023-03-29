<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import { computed, onMounted, reactive, ref, watch } from 'vue'

    import {Head, usePage} from '@inertiajs/inertia-vue3'
    import { Column } from '../Interfaces/Column'
    import axios, {AxiosPromise, AxiosResponse} from 'axios'
    import { Leistung } from '../Interfaces/Leistung'
    import FachbezogeneBemerkungenIndicator from '../Components/FachbezogeneBemerkungenIndicator.vue'
    import NoteInput from '../Components/NoteInput.vue'

    import {
        baseColumns,
        fachbezogeneBemerkungenColumns,
        mahnungenColumns,
        fehlstundenColumns,
        fachlehrerColumns,
        notenColumns,
        teilleistungenColumns,
    } from '../Helpers/columns.helper'

    import {
        SvwsUiCheckbox,
        SvwsUiTextInput,
        SvwsUiSelectInput,
        SvwsUiIcon,
        SvwsUiDataTable,
        SvwsUiButton,
    } from '@svws-nrw/svws-ui'
    import MahnungIndicatorReadonly from '../Components/MahnungIndicatorReadonly.vue'
    import MahnungIndicator from '../Components/MahnungIndicator.vue'
    import FachbezogeneBemerkungenIndicatorReadonly from '../Components/FachbezogeneBemerkungenIndicatorReadonly.vue'
    import FehlstundenInput from '../Components/FehlstundenInput.vue'

    const title = 'Notenmanager - Leistungsdatenübersicht'


    const getToggleValue = (column: string): boolean => usePage().props.value.settings[column] == 1

    let toggles = <{
        fachlehrer: boolean,
        bemerkungen: boolean,
        mahnungen: boolean,
        teilleistungen: boolean,
    }>reactive({
        fachlehrer: getToggleValue('leistungdatenuebersicht_teilleistungen'),
        bemerkungen: getToggleValue('leistungdatenuebersicht_fachlehrer'),
        mahnungen: getToggleValue('leistungdatenuebersicht_mahnungen'),
        teilleistungen: getToggleValue('leistungdatenuebersicht_bemerkungen'),
    })

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
        note: '0',
    })

    const columns = ref<Column[]>([])

    const drawTable = (): void => {
        const pushTable = (pushable: boolean, array: Array<Column>): void => {
            if (pushable) {
                array.forEach((column: Column): number => columns.value.push(column))
            }
        }

        columns.value.length = 0
        pushTable(true, baseColumns)
        pushTable(toggles.fachlehrer, fachlehrerColumns)
        pushTable(toggles.teilleistungen, teilleistungenColumns)
        pushTable(true, notenColumns)
        pushTable(toggles.mahnungen, mahnungenColumns)
        pushTable(true, fehlstundenColumns)
        pushTable(toggles.bemerkungen, fachbezogeneBemerkungenColumns)
    }

    watch(toggles, (): void => drawTable())

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

    const getLeistungen = (): AxiosPromise => axios
        .get(route('api.leistungsdatenuebersicht'))
        .then((response: AxiosResponse): AxiosResponse => state.leistungen = mapLeistungen(response.data))
        .finally(() => getFilters())

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

    let lowScoreArray: Array<string> = [ // TODO: Create a helper
        '6', '5-', '5', '5+', '4-',
    ]

    const lowScore = (note: string): boolean => lowScoreArray.includes(note)

    let leistungEdit = ref(false)
    let lehrerCanOverrideFachlehrer = (usePage().props.value.settings['lehrer_can_override_note'] == 1)

    const leistungEditToggle = () => {
        if (lehrerCanOverrideFachlehrer) {
            leistungEdit.value = !leistungEdit.value
        }
    }

    const updateFachbezogeneBemerkungen = (fb: string, data: Leistung): string => data.fachbezogeneBemerkungen = fb
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <AppLayout title="Leistungsdatenuebersicht">
        <template #main>
            <header class="header">
                <div class="header__headline">
                    <div class="header__headline__left">
                        <div id="headline">
                            <h2 class="text-headline">{{ title }}</h2>
                        </div>
                        <div class="header__toggles">
                            <SvwsUiCheckbox v-model="toggles.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                            <SvwsUiCheckbox v-model="toggles.fachlehrer" :value="true">Fachlehrer</SvwsUiCheckbox>
                            <SvwsUiCheckbox v-model="toggles.mahnungen" :value="true">Mahnungen</SvwsUiCheckbox>
                            <SvwsUiCheckbox v-model="toggles.bemerkungen" :value="true">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                        </div>
                    </div>
                    <div>
                        <SvwsUiButton @click="leistungEditToggle()" v-if="lehrerCanOverrideFachlehrer" :type="leistungEdit ? 'secondary' : 'primary'" size="big">Bearbeiten</SvwsUiButton>
                    </div>
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

            <h3 class="text-headline-sm mx-6" v-if="filteredLeistungen.length === 0">Keine Einträge gefunden!</h3>

            <SvwsUiDataTable v-else :items="filteredLeistungen" :columns="columns" clickable>
                <template #cell(note)="{ rowData }">
                    <div class="input-override" v-if="leistungEdit">
                        <NoteInput :leistung="rowData" :key="rowData.id"></NoteInput>
                        <SvwsUiIcon>
                            <mdi-warning-outline></mdi-warning-outline>
                        </SvwsUiIcon>
                    </div>
                    <strong :class="{ 'low-score' : lowScore(rowData.note) }" v-else>
                        {{ rowData.note }}
                    </strong>
                </template>

                <template #cell(fach)="{ rowData }">
                    <strong>{{ rowData.fach }}</strong>
                </template>

                <template #cell(istGemahnt)="{ rowData }">
                    <div class="input-override" v-if="leistungEdit">
                        <MahnungIndicator :leistung="rowData" :key="rowData.id" :disabled="false"></MahnungIndicator>
                        <SvwsUiIcon>
                            <mdi-warning-outline></mdi-warning-outline>
                        </SvwsUiIcon>
                    </div>
                    <MahnungIndicatorReadonly :leistung="rowData" :disabled="true" v-else></MahnungIndicatorReadonly>
                </template>



                <template #cell(fs)="{ rowData }">
                    <div class="input-override" v-if="leistungEdit">
                        <FehlstundenInput :model="rowData" column="fs"></FehlstundenInput>
                        <SvwsUiIcon>
                            <mdi-warning-outline></mdi-warning-outline>
                        </SvwsUiIcon>
                    </div>
                    <span v-else>{{ rowData.fs }}</span>
                </template>

                <template #cell(ufs)="{ rowData }">
                    <div class="input-override" v-if="leistungEdit">
                        <FehlstundenInput :model="rowData" column="ufs"></FehlstundenInput>
                        <SvwsUiIcon>
                            <mdi-warning-outline></mdi-warning-outline>
                        </SvwsUiIcon>
                    </div>
                    <span v-else>{{ rowData.ufs }}</span>
                </template>



                <template #cell(fachbezogeneBemerkungen)="{ rowData }">
                    <div class="input-override" v-if="leistungEdit">
                        <FachbezogeneBemerkungenIndicator :leistung="rowData" @updated="updateFachbezogeneBemerkungen($event, rowData)"></FachbezogeneBemerkungenIndicator>
                        <SvwsUiIcon>
                            <mdi-warning-outline></mdi-warning-outline>
                        </SvwsUiIcon>
                    </div>
                    <FachbezogeneBemerkungenIndicatorReadonly :leistung="rowData" v-else></FachbezogeneBemerkungenIndicatorReadonly>
                </template>
            </SvwsUiDataTable>
        </template>
    </AppLayout>
</template>

<style scoped>
.span {
    @apply ui-w-screen
}
.header {
    @apply ui-flex ui-flex-col ui-gap-4 ui-p-6
}

.header__headline {
    @apply ui-flex ui-gap-6 ui-justify-between
}

.header__headline__left {
    @apply ui-flex ui-flex-col ui-gap-4
}

.header__toggles {
    @apply ui-flex ui-items-center ui-justify-start ui-gap-3 ui-flex-wrap
}


header #headline {
    @apply ui-flex ui-items-center ui-justify-start ui-gap-6
}

header #filters {
    @apply ui-grid sm:ui-grid-cols-2 md:ui-grid-cols-3 lg:ui-grid-cols-6 ui-gap-6
}

header #header {
    @apply ui-flex ui-gap-6 ui-justify-between
}


 .low-score {
     @apply ui-text-red-500 ui-font-bold
 }

 .input-override {
     @apply ui-flex ui-gap-6 ui-justify-between ui-items-center ui-w-full
 }

 .input-override svg {
     @apply ui-text-red-600
 }
</style>
