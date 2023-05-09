<script setup lang="ts">
import {computed, onMounted, Ref, ref, watch} from 'vue'
    import axios, { AxiosError, AxiosResponse } from 'axios'
    import { Floskel, TableColumn, Schueler } from '../types'

    import {
        searchFilter,
        formatStringBasedOnGender,
        closeEditor,
        addSelectedFloskelnToBemerkung,
        selectFloskeln,
        saveBemerkung,
    } from '../Helpers/bemerkungen.helper'

    import {
        SvwsUiTextareaInput,
        SvwsUiDataTable,
        SvwsUiTextInput,
        SvwsUiButton,
    } from '@svws-nrw/svws-ui'

    const emit = defineEmits(['close', 'updated'])
    const props = defineProps<{
        schueler: Schueler,
        floskelgruppe: string,
    }>()

    const bemerkung: Ref<string> = ref(props.schueler[props.floskelgruppe.toUpperCase()])
    const storedBemerkung: Ref<string> = ref(props.schueler[props.floskelgruppe.toUpperCase()])
    const isDirty: Ref<bool> = ref(false)
    const readonly: Ref<bool> = ref(!props.schueler.matrix['editable_' + props.floskelgruppe])

    const searchTerm: Ref<string> = ref('')

    const floskeln: Ref<Floskel[]> = ref([])
    const selectedFloskeln: Ref<Floskel[]> = ref([])

    const columns: Ref<TableColumn[]> = ref([
        { key: 'id', label: 'ID', sortable: true },
        { key: 'kuerzel', label: 'Kuerzel', sortable: true },
        { key: 'text', label: 'Text', sortable: true, span: 5 },
    ])

    const types: { asv: string, aue: string, zb: string } = {
        asv: 'Arbeits und Sozialverhalten',
        aue: 'Außerunterrichtliches Engagement',
        zb: 'Zeugnisbemerkung',
    }

    watch(() => props.schueler, (): void =>
        bemerkung.value = storedBemerkung.value = props.schueler[props.floskelgruppe.toUpperCase()]
    )

    onMounted((): void =>
        axios.get(route('api.floskeln', props.floskelgruppe.toUpperCase()))
            .then((response: AxiosResponse): AxiosResponse =>
                floskeln.value = response.data
            )
            .catch((error: AxiosError): void => {
                alert('Ein Fehler ist aufgetreten.')
                console.log(error)
    }))

    watch(() => props.floskelgruppe, (): void => {
        bemerkung.value = storedBemerkung.value = props.schueler[props.floskelgruppe.toUpperCase()]
        axios.get(route('api.floskeln', props.floskelgruppe.toUpperCase()))
            .then((response: AxiosResponse): AxiosResponse =>
                floskeln.value = response.data
            )
            .catch((error: AxiosError): void => {
                alert('Ein Fehler ist aufgetreten.')
                console.log(error)
        })
    })

    const computedBemerkung = computed((): string | void => {
        isDirty.value = bemerkung.value != storedBemerkung.value
        if (!bemerkung.value) return
        return formatStringBasedOnGender(bemerkung.value, props.schueler)
    })

    const computedFloskeln = computed((): Array<Floskel> =>
        floskeln.value.filter((floskel: Floskel): boolean => searchFilter(floskel, searchTerm.value))
    )

    const save = (): void => saveBemerkung('api.schueler_bemerkung', props.schueler.id,
        { key: props.floskelgruppe.toUpperCase(), value: bemerkung.value }, bemerkung, storedBemerkung, isDirty,
        (): void => emit('updated', bemerkung.value)
    )

    const addSelected = (): void => addSelectedFloskelnToBemerkung(bemerkung, selectedFloskeln)
    const select = (floskeln: Floskel[]): void => selectFloskeln(floskeln, selectedFloskeln)
    const close = (): void => closeEditor(isDirty, () => emit('close'))
</script>

<template>
    <div class="content">
        <h2 class="text-headline">{{ types[props.floskelgruppe] }}</h2>
        <h1 class="text-headline-xl text-primary">{{ props.schueler.name }}</h1>

        <SvwsUiTextareaInput
            :modelValue="computedBemerkung"
            @update:modelValue="bemerkung = $event"
            autoresize
            :disabled="readonly"
        ></SvwsUiTextareaInput>

        <div class="buttons">
            <SvwsUiButton
                v-if="!readonly"
                @click="addSelected"
                :disabled="selectedFloskeln.length === 0"
            >
                Zuweisen
            </SvwsUiButton>

            <SvwsUiButton
                v-if="!readonly"
                @click="save"
                :disabled="!isDirty"
            >
                Speichern
            </SvwsUiButton>

            <SvwsUiButton
                @click="close"
                :type="isDirty ? 'danger' : 'secondary'"
            >
                Schließen
            </SvwsUiButton>
        </div>

        <SvwsUiDataTable
            :modelValue="selectedFloskeln"
            :items="computedFloskeln"
            :columns="columns"
            :selectable="true"
            @update:modelValue="select($event)"
            v-if="!readonly"
        >
            <template #search>
                <SvwsUiTextInput
                    type="search"
                    v-model="searchTerm"
                    placeholder="Suche"
                ></SvwsUiTextInput>
            </template>
        </SvwsUiDataTable>
    </div>
</template>

<style scoped>
    .content {
        @apply ui-p-6 ui-flex ui-flex-col ui-gap-6
    }

    .buttons {
        @apply ui-flex ui-justify-end ui-gap-3
    }
</style>
