<script setup lang="ts">
    import { computed, onMounted, Ref, ref, watch } from 'vue'
    import axios, { AxiosError, AxiosResponse } from 'axios'
    import { Floskel } from '@/Interfaces/Floskel'
    import { floskelgruppen } from '@/Interfaces/Floskelgruppe'
    import { Schueler } from '@/Interfaces/Schueler'
    import { SvwsUiTextareaInput, SvwsUiTextInput, SvwsUiButton, SvwsUiTable, DataTableColumn } from '@svws-nrw/svws-ui'
    import { formatBasedOnGender, closeEditor, addSelectedToBemerkung, pasteShortcut, search } from '@/Helpers/bemerkungen.helper'

    const props = defineProps<{
        schueler: Schueler,
        floskelgruppe: string,
        bemerkung: string|null,
    }>()

    const emit = defineEmits<{
        (event: 'close'): void;
        (event: 'updated', value: string|null): void;
    }>()

    const columns: Ref<DataTableColumn[]> = ref([
        { key: 'id', label: 'ID', sortable: true },
        { key: 'kuerzel', label: 'Kuerzel', sortable: true },
        { key: 'text', label: 'Text', sortable: true, span: 5 },
    ])

    const rows: Ref<Floskel[]> = ref([])
    const selectedRows: Ref<Floskel[]> = ref([])

    const searchFilter: Ref<string> = ref('')
    const bemerkung: Ref<string|null> = ref(props.bemerkung)
    const storedBemerkung: Ref<string|null> = ref(props.bemerkung)

    const isDirty: Ref<boolean> = ref(false)
    const isEditable: Ref<boolean> = ref(true)

    onMounted((): Promise<AxiosResponse | void> => fetchFloskeln())

    watch((): string => props.floskelgruppe, (): Promise<AxiosResponse | void> => fetchFloskeln())

    watch((): string|null => props.bemerkung, (): void => {
        bemerkung.value = storedBemerkung.value = props.bemerkung
        isDirty.value = false
    })

    watch((): string|null => bemerkung.value, (): void => {
        isDirty.value = storedBemerkung.value !== bemerkung.value
        bemerkung.value = formatBasedOnGender(bemerkung.value, props.schueler)
    })

    watch((): Schueler => props.schueler, (): boolean =>
        isEditable.value = props.schueler.matrix['editable_' + props.floskelgruppe]
    )

    const fetchFloskeln = (): Promise<AxiosResponse | void> => axios
        .get(route('api.floskeln', props.floskelgruppe.toUpperCase()))
        .then((response: AxiosResponse): AxiosResponse => rows.value = response.data)
        .catch((error: AxiosError): void => {
            alert('Ein Fehler ist aufgetreten.')
            console.log(error)
        })

    // Table filtering options
    const filterReset = (): string => searchFilter.value = ''
    const filtered = (): boolean => '' !== searchFilter.value

    const rowsFiltered = computed((): Floskel[] => rows.value.filter((floskel: Floskel): boolean =>
        search(searchFilter, floskel.kuerzel) || search(searchFilter, floskel.text)
    ))

    // Button actions
    const add = (): void => addSelectedToBemerkung(bemerkung, selectedRows)
    const close = (): void => closeEditor(isDirty, (): void => emit('close'))
    const save = (): Promise<void> => axios.post(
        route('api.schueler_bemerkung', props.schueler.id),
        { key: props.floskelgruppe.toUpperCase(), value: bemerkung.value }
    ).then((): void => {
        storedBemerkung.value = bemerkung.value
        isDirty.value = false
        emit('updated', bemerkung.value)
    }).catch((error: AxiosError): void => {
        alert('Ein Fehler ist aufgetreten.')
        console.log(error)
    })

    // Textarea actions
    const onChange = (text: string): string => bemerkung.value = text
    const onKeyDown = (event: KeyboardEvent): void => pasteShortcut(event, bemerkung, rows)

</script>

<template>
    <h1>{{ props.schueler.name }}</h1>
    <h2>{{ floskelgruppen[props.floskelgruppe] }}</h2>

    <SvwsUiTextareaInput
        v-model="bemerkung"
        placeholder="Bemerkung"
        resizeable="vertical"
        :disabled="!isEditable"
        @change="onChange"
        @keydown="onKeyDown"
    />

    <SvwsUiButton
        v-if="isEditable"
        @click="add"
        :disabled="selectedRows.length === 0"
    >Zuweisen</SvwsUiButton>

    <SvwsUiButton
        v-if="isEditable"
        :disabled="!isDirty"
        @click="save"
    >Speichern</SvwsUiButton>

    <SvwsUiButton
        @click="close"
        :type="isDirty && isEditable ? 'danger' : 'secondary'"
    >Schließen</SvwsUiButton>

    <SvwsUiTable
        v-model="selectedRows"
        :items="rowsFiltered"
        :columns="columns"
        :clickable="true"
        :selectable="isEditable"
        :count="true"
        :filtered="filtered()"
        :filterReset="filterReset"
    >
        <template #filterAdvanced>
            <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
        </template>
    </SvwsUiTable>
</template>

<style scoped></style>
