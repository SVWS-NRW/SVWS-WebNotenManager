<script setup lang="ts">
    import AppLayout from '../Layouts/AppLayout.vue'
    import {computed, onMounted, reactive, Ref, ref, watch} from 'vue'

    import { Head, usePage } from '@inertiajs/inertia-vue3'
    import { Column } from '../Interfaces/Column'
    import axios, {AxiosPromise, AxiosResponse} from 'axios'
    import { Leistung } from '../Interfaces/Leistung'
    import NoteInput from '../Components/NoteInput.vue'
    import BemerkungIndicator from '../Components/BemerkungIndicator.vue'

    import { tableCellEditable } from '../Helpers/pages.helper'

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
        SvwsUiTooltip,
    } from '@svws-nrw/svws-ui'

    import MahnungIndicatorReadonly from '../Components/MahnungIndicatorReadonly.vue'
    import MahnungIndicator from '../Components/MahnungIndicator.vue'
    import FehlstundenInput from '../Components/FehlstundenInput.vue'
    import FbEditor from '../Components/FbEditor.vue'
    import {Auth} from '../Interfaces/Auth'

    const title = 'Notenmanager - Leistungsdatenübersicht'

    const getToggleValue = (column: string): boolean => usePage().props.value.settings.filters[column] == 1

    const selectedFbLeistung: Ref<Leistung | null> = ref(null)

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


    const auth: Auth = usePage().props.value.auth

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
    let lehrerCanOverrideFachlehrer = (usePage().props.value.settings.matrix['lehrer_can_override_fachlehrer'] == 1)

    const leistungEditToggle = () => {
        if (lehrerCanOverrideFachlehrer) {
            leistungEdit.value = !leistungEdit.value
        }
    }

    const editable = (condition: boolean): boolean => tableCellEditable(condition, auth.administrator, leistungEdit.value) // ok
    const readonly = (leistung: Leistung, permission: 'editable_fb'): boolean => !editable(leistung.matrix[permission])
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <AppLayout title="Leistungsdatenuebersicht">

        <template v-slot:aside v-if="selectedFbLeistung">
            <FbEditor
                :leistung="selectedFbLeistung"
                :readonly="readonly(selectedFbLeistung, 'editable_fb')"
                @close="selectedFbLeistung = null"
                @updated="selectedFbLeistung.fachbezogeneBemerkungen = $event; drawTable()"
            ></FbEditor>
        </template>

        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">{{ title }}</h2>
                </div>
                <div id="toggles">
                    <SvwsUiCheckbox v-model="toggles.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.fachlehrer" :value="true">Fachlehrer</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.mahnungen" :value="true">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="toggles.bemerkungen" :value="true">Fachbezogene Bemerkungen</SvwsUiCheckbox>

                    <SvwsUiButton @click="leistungEditToggle()" v-if="lehrerCanOverrideFachlehrer" :type="leistungEdit ? 'primary' : 'secondary'" size="big">
                        <SvwsUiIcon>
                            <mdi-pencil></mdi-pencil>
                        </SvwsUiIcon>
                    </SvwsUiButton>
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
                <template #header(fs)="{ column: { label } }">
                    <SvwsUiTooltip>
                        FS
                        <template #content>
                            Fachbezogene Fehlstunden
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #header(fsu)="{ column: { label } }">
                    <SvwsUiTooltip>
                        FSU
                        <template #content>
                            Unentschuldigte fachbezogene Fehlstunden
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #header(fachbezogeneBemerkungen)="{ column: { label } }">
                    <SvwsUiTooltip>
                        FB
                        <template #content>
                            Fachbezogene Bemerkungen
                        </template>
                    </SvwsUiTooltip>
                </template>

                <template #cell(fach)="{ rowData }">
                    <button type="button" @click="selectedFbLeistung = rowData" class="truncate">
                        <strong>{{ rowData.fach }}</strong>
                    </button>
                </template>

                <template #cell(klasse)="{ rowData }">
                    <button type="button" @click="selectedFbLeistung = rowData" class="truncate">
                        {{ rowData.klasse }}
                    </button>
                </template>

                <template #cell(name)="{ rowData }">
                    <button type="button" @click="selectedFbLeistung = rowData" class="truncate">
                        {{ rowData.name }}
                    </button>
                </template>

                <template #cell(kurs)="{ rowData }">
                    <button type="button" @click="selectedFbLeistung = rowData" class="truncate">
                        {{ rowData.kurs }}
                    </button>
                </template>

                <template #cell(lehrer)="{ rowData }">
                    <button type="button" @click="selectedFbLeistung = rowData" class="truncate">
                        {{ rowData.lehrer }}
                    </button>
                </template>

                <template #cell(teilnoten)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_teilnoten) }">
                        TBD
                    </div>
                </template>

                <template #cell(note)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_noten) }">
                        <NoteInput :leistung="rowData" :key="rowData.id" v-if="editable(rowData.matrix.editable_noten)"></NoteInput>
                        <strong :class="{ 'low-score' : lowScore(rowData.note) }" v-else>
                            {{ rowData.note }}
                        </strong>
                    </div>
                </template>

                <template #cell(istGemahnt)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_mahnungen) }">
                        <MahnungIndicator :leistung="rowData" :key="rowData.id" :disabled="false" v-if="editable(rowData.matrix.editable_mahnungen)"></MahnungIndicator>
                        <MahnungIndicatorReadonly :leistung="rowData" :disabled="true" v-else></MahnungIndicatorReadonly>
                    </div>
                </template>

                <template #cell(fs)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_fehlstunden && rowData.matrix.toggleable_fehlstunden) }">
                        <FehlstundenInput :model="rowData" column="fs" v-if="editable(rowData.matrix.editable_fehlstunden && rowData.matrix.toggleable_fehlstunden)"></FehlstundenInput>
                        <strong v-else>{{ rowData.fs }}</strong>
                    </div>
                </template>

                <template #cell(fsu)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_fehlstunden && rowData.matrix.toggleable_fehlstunden) }">
                        <FehlstundenInput :model="rowData" column="fsu" v-if="editable(rowData.matrix.editable_fehlstunden && rowData.matrix.toggleable_fehlstunden)"></FehlstundenInput>
                        <strong v-else>{{ rowData.fsu }}</strong>
                    </div>
                </template>

                <template #cell(fachbezogeneBemerkungen)="{ rowData }">
                    <div class="cell cell__input" :class="{ 'cell--editable': editable(rowData.matrix.editable_fb) }">
                        <BemerkungIndicator
                            :model="rowData"
                            :bemerkung="rowData.fachbezogeneBemerkungen"
                            @clicked="selectedFbLeistung = rowData"
                        ></BemerkungIndicator>
                    </div>
                </template>
            </SvwsUiDataTable>
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

header #toggles {
    @apply ui-flex ui-items-center ui-justify-start ui-gap-3 ui-flex-wrap
}

header #headline {
    @apply ui-flex ui-items-center ui-justify-start ui-gap-6
}

header #filters {
    @apply ui-grid sm:ui-grid-cols-2 md:ui-grid-cols-3 lg:ui-grid-cols-6 ui-gap-6
}

/*.editable {*/
/*    @apply ui-bg-grey ui-w-full ui-block ui-h-full*/
/*}*/

/*.header {*/
/*    @apply ui-flex ui-flex-col ui-gap-4 ui-p-6*/
/*}*/

/*.header__headline {*/
/*    @apply ui-flex ui-gap-6 ui-justify-between*/
/*}*/

/*.header__headline__left {*/
/*    @apply ui-flex ui-flex-col ui-gap-4*/
/*}*/

/*.header__toggles {*/
/*    @apply ui-flex ui-items-center ui-justify-start ui-gap-3 ui-flex-wrap*/
/*}*/


/*header #headline {*/
/*    @apply ui-flex ui-items-center ui-justify-start ui-gap-6*/
/*}*/

/*header #filters {*/
/*    @apply ui-grid sm:ui-grid-cols-2 md:ui-grid-cols-3 lg:ui-grid-cols-6 ui-gap-6*/
/*}*/

/*header #header {*/
/*    @apply ui-flex ui-gap-6 ui-justify-between*/
/*}*/


/* .low-score {*/
/*     @apply ui-text-red-500 ui-font-bold*/
/* }*/

/* .input-override {*/
/*     @apply ui-flex ui-gap-6 ui-justify-between ui-items-center ui-w-full*/
/* }*/

/* .input-override svg {*/
/*     @apply ui-text-red-600*/
/* }*/
</style>
