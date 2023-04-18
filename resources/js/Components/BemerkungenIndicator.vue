<script setup lang="ts"> // ok
    import { computed, reactive, Ref, ref } from 'vue'
    import axios, {AxiosError, AxiosPromise, AxiosResponse} from 'axios'
    import { Leistung } from '../Interfaces/Leistung'
    import { Column } from '../Interfaces/Column'
    import { Floskel } from '../Interfaces/Floskel'
    import { formatStringBasedOnGender } from '../Helpers/string.helper'

    import {
        SvwsUiIcon,
        SvwsUiTextareaInput,
        SvwsUiButton,
        SvwsUiBadge,
        SvwsUiDataTable,
        SvwsUiTextInput,
    } from '@svws-nrw/svws-ui'

    const changesNotSavedWarning: string = 'Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren.'

    const props = defineProps<{
        leistung: Leistung,
        floskelgruppe: string
    }>()

    let state: { bemerkung: string, storedBemerkung: string, search: string, isDirty: boolean } = reactive({
        bemerkung: <string> props.leistung[props.floskelgruppe],
        storedBemerkung: <string> props.leistung[props.floskelgruppe],
        search: '',
        isDirty: false,
    })

    const computedFloskeln = computed(() =>
        floskeln.value.filter((floskel: Floskel): boolean => floskelFilter(floskel))
    );

    const floskelFilter = (floskel: Floskel): boolean => {
        if (state.search == '') return true

        const includes = (column: string): boolean => floskel[column].toLowerCase().includes(state.search.toLowerCase())

        return includes('text') || includes('kuerzel')
    }

    const floskeln: Ref<Floskel[]> = ref([])
    const selectedRows: Ref<Floskel[]> =  ref([])
    const columns: Ref<Column[]> = ref([
        { key: 'id', label: 'id', sortable: true },
        { key: 'kuerzel', label: 'Kuerzel', sortable: true },
        { key: 'text', label: 'Text', sortable: true },
    ])

    const collapsed: Ref<boolean> = ref(true)

    const open = (): AxiosPromise => getBemerkungen()

    const close = (): void => {
        if (state.isDirty ? confirm(changesNotSavedWarning) : true) {
            collapsed.value = true
            state.bemerkung = state.storedBemerkung
        }
    }

    const computedBemerkung = computed((): string => {
        state.isDirty = state.bemerkung != state.storedBemerkung
        if (!state.bemerkung) return
        return formatStringBasedOnGender(state.bemerkung, props.leistung)
    })

    const getBemerkungen = (): AxiosPromise => axios
        .get(route('api.floskeln', props.floskelgruppe))
        .then((response: AxiosResponse): AxiosResponse => {
            floskeln.value = response.data
            collapsed.value = false
        })
        .catch((error: AxiosError): AxiosResponse => {
            alert('Ein Fehler ist aufgetreten.')
            console.log(error)
        })

    const setBemerkungen = (): AxiosPromise => axios
        .post(
            route('api.schueler_bemerkung', props.leistung.id),
            { key: props.floskelgruppe, value: state.bemerkung }
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
        let bemerkung: string = selectedRows.value.map((selected: Floskel): string => selected.text).join(' ')
        state.bemerkung = [state.bemerkung, bemerkung].join(' ').trim()
        selectedRows.value = []
    }

    const selectFloskel = (floskel: Floskel): void => {
        selectedRows.value = []
        floskel.forEach(floskel => selectedRows.value.push(floskel))
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

                            <SvwsUiDataTable :items="computedFloskeln" :columns="columns" :footer="true" :selectable="true" :clickable="true" @update:modelValue="selectFloskel" :modelValue="selectedRows">
                                <template #search>
                                    <SvwsUiTextInput type="search" v-model="state.search" placeholder="Suche"></SvwsUiTextInput>
                                </template>
                                <template #footerActions>
                                    <SvwsUiButton @click="addFloskelToBemerkung" :type="selectedRows.length ? 'primary' : 'secondary'">Zuweisen</SvwsUiButton>
                                </template>
                            </SvwsUiDataTable>
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