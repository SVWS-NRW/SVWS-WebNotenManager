<script setup lang="ts">
import {computed, onMounted, reactive, ref, Ref, watch} from 'vue'
    import axios, {AxiosError, AxiosResponse} from 'axios'
    import { Leistung } from '../Interfaces/Leistung'
    import {FachbezogeneFloskel} from '../Interfaces/FachbezogeneFloskel'
    import {Column} from '../Interfaces/Column'
    import { FachbezogeneFloskelnFilterOptions, FachbezogeneFloskelnFilterValues } from '../Interfaces/Filter'
    import { formatStringBasedOnGender } from '../Helpers/string.helper'

    import {
        SvwsUiTextareaInput,
        SvwsUiButton,
        SvwsUiDataTable,
        SvwsUiTextInput,
        SvwsUiSelectInput,
    } from '@svws-nrw/svws-ui'

    const changesNotSavedWarning: string = 'Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren.'

    const emit = defineEmits(['close', 'updated'])

    const props = defineProps<{
        leistung: Leistung,
        readonly: boolean,
    }>()

    const state = reactive({
        bemerkung: <string> props.leistung.fachbezogeneBemerkungen,
        storedBemerkung: <string> props.leistung.fachbezogeneBemerkungen,
        isDirty: false,
    })

    const floskeln: Ref<FachbezogeneFloskel[]> = ref([])
    const selectedFloskeln: Ref<FachbezogeneFloskel[]> = ref([])
    const columns: Ref<Column[]> = ref([
        { key: 'kuerzel', label: 'Kürzel', sortable: true, minWidth: 6 },
        { key: 'text', label: 'Text', sortable: true, span: 5 },
        { key: 'niveau', label: 'Niveau', sortable: true, minWidth: 6 },
        { key: 'jahrgang', label: 'Jahrgang', sortable: true, minWidth: 8 },
    ])

    let filterOptions = <FachbezogeneFloskelnFilterOptions>reactive({
        'niveau': [],
        'jahrgaenge': [],
    })

    let filters = <FachbezogeneFloskelnFilterValues>reactive({
        search: '',
        niveau: 0,
        jahrgang: 0,
    })

    watch(() => props.leistung, (): string => state.bemerkung = props.leistung.fachbezogeneBemerkungen || '')

    onMounted((): void => {
        axios
            .get(route('api.fachbezogene_floskeln', props.leistung.fach_id))
            .then((response: AxiosResponse): void => {
                floskeln.value = response.data?.data || []
                filterOptions.niveau = response.data?.niveau || []
                filterOptions.jahrgaenge = response.data?.jahrgaenge || []
            })
            .catch((error: AxiosError): void => {
                alert('Ein Fehler ist aufgetreten.')
                console.log(error)
            })
    })

    const computedFloskeln = computed((): Array<FachbezogeneFloskel> =>
        floskeln.value.filter((floskel: FachbezogeneFloskel): boolean =>
            searchFilter(floskel)
            && tableFilter(floskel, 'niveau', true)
            && tableFilter(floskel, 'jahrgang', true)
        )
    )

    const computedBemerkung = computed((): string | void => {
        state.isDirty = state.bemerkung != state.storedBemerkung
        if (!state.bemerkung) return
        return formatStringBasedOnGender(state.bemerkung, props.leistung)
    })

    const tableFilter = (floskel: FachbezogeneFloskel, column: 'jahrgang' | 'niveau', containsOnlyEmptyOption: boolean = false): boolean => {
        if (containsOnlyEmptyOption && filters[column] == null) return floskel[column] == null
        if (filters[column] == 0) return true
        return floskel[column] == filters[column]
    }

    const searchFilter = (floskel: FachbezogeneFloskel): boolean => {
        if (filters.search === '') return true
        const search = (search: string) => search.toLowerCase().includes(filters.search.toLowerCase())
        return search(floskel.text) || search(floskel.kuerzel)
    }

    const saveBemerkung = (): Promise<void> => axios
        .post(route('api.fachbezogene_bemerkung', props.leistung.id), { bemerkung: state.bemerkung })
        .then((): void => {
            state.storedBemerkung = state.bemerkung
            state.isDirty = false
            emit('updated', state.bemerkung)
        })
        .catch((error: AxiosError): void => {
            alert('Ein Fehler ist aufgetreten.')
            console.log(error)
        })

    const selectFloskel = (floskel: FachbezogeneFloskel[]): void => {
        selectedFloskeln.value = []
        floskel.forEach((floskel: FachbezogeneFloskel): Number => selectedFloskeln.value.push(floskel))
    }

    const addSelectedToBemerkung = (): void => {
        let bemerkung: string = selectedFloskeln.value.map(
            (selected: FachbezogeneFloskel): string => selected.text
        ).join(' ')

        state.bemerkung = [state.bemerkung, bemerkung].join(' ').trim()
        selectedFloskeln.value = []
    }

    const close = (): void => {
        if (state.isDirty ? confirm(changesNotSavedWarning) : true) {
            emit('close')
        }
    }
</script>

<template>
    <div class="container">
        <h2 class="text-headline">{{ props.leistung.fach }} Fachbezogene Bemerkungen</h2>
        <h1 class="text-headline-xl text-primary">{{ props.leistung.name }}</h1>

        <SvwsUiTextareaInput
            :modelValue="computedBemerkung"
            @update:modelValue="state.bemerkung = $event"
            autoresize
            :disabled="props.readonly"
        ></SvwsUiTextareaInput>

        <div class="buttons">
            <SvwsUiButton
                v-if="!readonly"
                @click="addSelectedToBemerkung"
                :disabled="selectedFloskeln.length === 0"
            >
                Zuweisen
            </SvwsUiButton>

            <SvwsUiButton
                v-if="!readonly"
                @click="saveBemerkung"
                :disabled="!state.isDirty"
            >
                Speichern
            </SvwsUiButton>

            <SvwsUiButton @click="close" :type="state.isDirty ? 'danger' : 'secondary'">
                Schließen
            </SvwsUiButton>
        </div>

        <SvwsUiDataTable
            :modelValue="selectedFloskeln"
            :items="computedFloskeln"
            :columns="columns"
            :selectable="true"
            @update:modelValue="selectFloskel"
            v-if="!readonly"
        >
            <template #search>
                <SvwsUiTextInput
                    type="search"
                    v-model="filters.search"
                    placeholder="Suche"
                ></SvwsUiTextInput>
            </template>

            <template #filter>
                <SvwsUiSelectInput
                    v-if="filterOptions.niveau"
                    placeholder="Niveau"
                    v-model="filters.niveau"
                    :options="filterOptions.niveau"
                ></SvwsUiSelectInput>

                <SvwsUiSelectInput
                    v-if="filterOptions.jahrgaenge"
                    placeholder="Jahrgang"
                    v-model="filters.jahrgang"
                    :options="filterOptions.jahrgaenge"
                ></SvwsUiSelectInput>
            </template>
        </SvwsUiDataTable>
    </div>
</template>

<style scoped>
    .container {
        @apply
            ui-p-6
            ui-flex
            ui-flex-col
            ui-gap-6
    }

    .buttons {
        @apply
            ui-flex
            ui-justify-end
            ui-gap-3
    }

</style>
