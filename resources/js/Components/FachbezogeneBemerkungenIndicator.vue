<script setup lang="ts"> // ok
    import { computed, reactive, Ref, ref } from 'vue'
    import axios, { AxiosError, AxiosPromise, AxiosResponse } from 'axios'
    import { Leistung } from '../Interfaces/Leistung'
    import { Column } from '../Interfaces/Column'
    import { FachbezogeneFloskel } from '../Interfaces/FachbezogeneFloskel'
    import { formatStringBasedOnGender } from '../Helpers/string.helper'
    import { FachbezogeneFloskelnFilterValues } from '../Interfaces/Filter'

    import {
        SvwsUiIcon,
        SvwsUiTextareaInput,
        SvwsUiButton,
        SvwsUiBadge,
        SvwsUiTable,
        SvwsUiTextInput,
        SvwsUiSelectInput,
    } from '@svws-nrw/svws-ui'

    const changesNotSavedWarning: string = 'Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren.'

    const props = defineProps<{
        leistung: Leistung
    }>()

    let state: { bemerkung: string, storedBemerkung: string, isDirty: boolean } = reactive({
        bemerkung: <string> props.leistung.fachbezogeneBemerkungen,
        storedBemerkung: <string> props.leistung.fachbezogeneBemerkungen,
        isDirty: false,
    })

    let filterOptions = <FachbezogeneFloskelnFilterValues>reactive({
        'niveau': [],
        'jahrgaenge': [],
    })

    let filters = <{
        search: string,
        niveau: Number,
        jahrgang: Number | string,
    }>reactive({
        search: '',
        niveau: 0,
        jahrgang: 0,
    })

    const computedFloskeln = computed((): Array<FachbezogeneFloskel> =>
        floskeln.value.filter((floskel: FachbezogeneFloskel): boolean =>
            searchFilter(floskel)
            && tableFilter(floskel, 'niveau', true)
            && tableFilter(floskel, 'jahrgang', true)
        )
    )

    const searchFilter = (floskel: FachbezogeneFloskel): boolean => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(floskel.text) || search(floskel.kuerzel)
    }

    const tableFilter = (floskel: FachbezogeneFloskel, column: string, containsOnlyEmptyOption: boolean = false): boolean => {
        if (containsOnlyEmptyOption && [null, ''].includes(filters[column])) return floskel[column] == null
        if (filters[column] == 0) return true
        return floskel[column] == filters[column]
    }

    const floskeln: Ref<FachbezogeneFloskel[]> = ref([])
    const selectedRows: Ref<FachbezogeneFloskel[]> = ref([])
    const columns: Ref<Column[]> = ref([
        { key: 'kuerzel', label: 'Kuerzel', sortable: true },
        { key: 'text', label: 'Text', sortable: true },
        { key: 'niveau', label: 'Niveau', sortable: true },
        { key: 'jahrgang', label: 'Jahrgang', sortable: true },
    ])

    const collapsed: Ref<boolean> = ref(true)

    const open = (): AxiosPromise => getFloskeln()

    const close = (): void => {
        if (state.isDirty ? confirm(changesNotSavedWarning) : true) {
            collapsed.value = true
            state.bemerkung = state.storedBemerkung
        }
    }

    const computedBemerkung = computed((): string => {
        state.isDirty = state.bemerkung != state.storedBemerkung

        if (!state.bemerkung) {
            return
        }

        return formatStringBasedOnGender(state.bemerkung, props.leistung)
    })

    const getFloskeln = (): AxiosPromise => axios
        .get(route('api.fachbezogene_floskeln', props.leistung.fach_id))
        .then((response: AxiosResponse): AxiosResponse => {
            floskeln.value = response.data?.data || []
            filterOptions.niveau = response.data?.niveau || []
            filterOptions.jahrgaenge = response.data?.jahrgaenge || []

            collapsed.value = false
            return
        })
        .catch((error: AxiosError): AxiosResponse => {
            alert('Ein Fehler ist aufgetreten.')
            console.log(error)
            return
        })

    const setBemerkungen = (): AxiosPromise => axios
        .post(
            route('api.fachbezogene_bemerkung', props.leistung.id),
            { bemerkung: state.bemerkung }
        ).then((): AxiosResponse => {
            state.storedBemerkung = state.bemerkung
            state.isDirty = false
            return
        })
        .catch((error: AxiosError): AxiosResponse => {
            alert('Ein Fehler ist aufgetreten.')
            console.log(error)
            return
        })

    const updateBemerkung = (bemerkung: string): string => state.bemerkung = bemerkung

    const addFloskelToBemerkung = (): void => {
        let bemerkung: string = selectedRows.value.map((selected: FachbezogeneFloskel): string => selected.text).join(' ')
        state.bemerkung = [state.bemerkung, bemerkung].join(' ').trim()
        selectedRows.value = []
}
</script>

<template>
    <div class="content-card--blockungsuebersicht ui-flex ui-h-full ui-content-start">
        <div id="bemerkung">
            <SvwsUiIcon @click="open()" class="ui-flex ui-items-center">
                <mdi-checkbox-marked-outline v-if="state.bemerkung"></mdi-checkbox-marked-outline>
                <mdi-checkbox-blank-outline v-else></mdi-checkbox-blank-outline>
            </SvwsUiIcon>
            {{ state.bemerkung }}
        </div>


        <div class="app-layout--main-sidebar" :class="{ 'app-layout--main-sidebar--collapsed': collapsed }">
            <div class="app-layout--main-sidebar--container" v-if="!collapsed">
                <div class="app-layout--main-sidebar--trigger" @click="close">
                    <div class="sidebar-trigger--text">
                        <SvwsUiButton type="icon" class="close-button" v-if="!collapsed">
                            <SvwsUiIcon>
                                <mdi-close></mdi-close>
                            </SvwsUiIcon>
                        </SvwsUiButton>

                        {{ props.leistung.nachname }}, {{ props.leistung.vorname }}
                        <SvwsUiBadge>{{ props.leistung.klasse }}</SvwsUiBadge>
                    </div>
                </div>
                <div class="app-layout--main-sidebar--content">
                    <div id="component">
                        <SvwsUiTextareaInput :modelValue="computedBemerkung" @update:modelValue="updateBemerkung"></SvwsUiTextareaInput>
                        <div id="buttons">
                            <SvwsUiButton @click="setBemerkungen" :type="state.isDirty ? 'primary' : 'secondary'">Speichern</SvwsUiButton>
                            <SvwsUiButton @click="close" v-show="state.isDirty" type="secondary">Verwerfen</SvwsUiButton>
                        </div>

                        <div id="floskel-container" v-if="floskeln.length">
                            <hr>
                            <h3 class="headline-3">Floskeln</h3>

                            <SvwsUiTextInput type="search" v-model="filters.search"  placeholder="Suche"></SvwsUiTextInput>
                            <SvwsUiSelectInput v-if="filterOptions.niveau" placeholder="Niveau" v-model="filters.niveau" :options="filterOptions.niveau"></SvwsUiSelectInput>
                            <SvwsUiSelectInput v-if="filterOptions.jahrgaenge" placeholder="Jahrgang" v-model="filters.jahrgang" :options="filterOptions.jahrgaenge"></SvwsUiSelectInput>

                            <SvwsUiTable v-if="computedFloskeln.length" :data="computedFloskeln" :columns="columns" v-model:selection="selectedRows" :footer="true" is-multi-select>
                                <template #footer>
                                    <SvwsUiButton @click="addFloskelToBemerkung" :type="selectedRows.length ? 'primary' : 'secondary'">Zuweisen</SvwsUiButton>
                                </template>
                            </SvwsUiTable>
                            <strong v-else>Keine Einträge gefunden!</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .close-button {
        @apply ui-mr-2 ui-p-[0.1em]
    }

    #buttons {
        @apply ui-flex ui-gap-3 ui-self-end
    }

    #component {
        @apply ui-flex ui-flex-col ui-gap-6
    }

    #floskel-container {
        @apply ui-flex ui-flex-col ui-gap-6
    }

    #bemerkung {
        @apply ui-flex ui-gap-1.5 ui-items-center ui-whitespace-nowrap ui-overflow-hidden
    }
</style>