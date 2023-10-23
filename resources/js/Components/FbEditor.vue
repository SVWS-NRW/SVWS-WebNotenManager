<script setup lang="ts">
    import { computed, onMounted, Ref, ref, watch, nextTick } from 'vue'
    import axios, { AxiosError, AxiosResponse } from 'axios'
    import { TableColumn, Leistung, FachbezogeneFloskel } from '@/types'

    import {
        addSelectedFloskelnToBemerkung,
        closeEditor,
        formatStringBasedOnGender,
        saveBemerkung,
        searchFilter,
        selectFloskeln,
        tableFilter,
        floskelPasteShortcut,
    } from '@/Helpers/bemerkungen.helper'

    import {
        SvwsUiTextareaInput,
        SvwsUiDataTable,
        SvwsUiTextInput,
        SvwsUiButton,
        //deprecated
        //SvwsUiSelectInput,
        SvwsUiMultiSelect,
    } from '@svws-nrw/svws-ui'

    const emit = defineEmits(['close', 'updated'])

    const props = defineProps<{
        leistung: Leistung,
        readonly: boolean,
    }>()

    const bemerkung: Ref<string | null> = ref(null)
    const storedBemerkung: Ref<string | null> = ref(null)
    const isDirty: Ref<boolean> = ref(false)
    const searchTerm: Ref<string> = ref('')

    const floskeln: Ref<FachbezogeneFloskel[]> = ref([])
    const selectedFloskeln: Ref<FachbezogeneFloskel[]> = ref([])

    const columns: Ref<TableColumn[]> = ref([
        { key: 'kuerzel', label: 'Kürzel', sortable: true, minWidth: 6 },
        { key: 'text', label: 'Text', sortable: true, span: 5 },
        { key: 'niveau', label: 'Niveau', sortable: true, minWidth: 6 },
        { key: 'jahrgang', label: 'Jahrgang', sortable: true, minWidth: 8 },
    ])

    const niveauOptions: Ref<{ index: Number, label: Number | string | null }> = ref([])
    const jahrgaengeOptions: Ref<{ index: Number, label: Number | string | null }> =  ref([])
    const niveauFilter: Ref<Number> = ref(0)
    const jahrgangFilter: Ref<Number> = ref(0)

    let filterOptions = ref({
        'niveau': [],
        'jahrgaenge': [],
    })

    const redrawBemerkungen = (): string | null =>
        bemerkung.value
            = storedBemerkung.value
            = props.leistung.fachbezogeneBemerkungen

    watch(() => props.leistung, (): string | null => redrawBemerkungen())

    onMounted((): void => {
        redrawBemerkungen()

        axios.get(route('api.fachbezogene_floskeln', props.leistung.fach_id))
            .then((response: AxiosResponse): void => {
                floskeln.value = response.data?.data || []
                niveauOptions.value = response.data?.niveau || []
                jahrgaengeOptions.value = response.data?.jahrgaenge || []
            })
            .catch((error: AxiosError): void => {
                alert('Ein Fehler ist aufgetreten.')
                console.log(error)
            })
    })

    const computedBemerkung = computed((): string | void => {
        isDirty.value = bemerkung.value != storedBemerkung.value
        if (!bemerkung.value) return
        return formatStringBasedOnGender(bemerkung.value, props.leistung)
    })

    const computedFloskeln = computed((): Array<FachbezogeneFloskel> =>
        floskeln.value.filter((floskel: FachbezogeneFloskel): boolean =>
            searchFilter(floskel, searchTerm.value)
                && tableFilter(floskel, 'niveau', niveauFilter, true)
                && tableFilter(floskel, 'jahrgang', jahrgangFilter, true)
        )
    )

    const save = (): Promise<void> => saveBemerkung('api.fachbezogene_bemerkung', props.leistung.id,
        { bemerkung: bemerkung.value }, bemerkung, storedBemerkung, isDirty,
        (): void => emit('updated', bemerkung.value)
    )

    const addSelected = (): void => addSelectedFloskelnToBemerkung(bemerkung, selectedFloskeln)
    const select = (floskeln: FachbezogeneFloskel[]): void => selectFloskeln(floskeln, selectedFloskeln)
    const close = (): void => closeEditor(isDirty, (): void => emit('close'))
    const onKeyDown = (event: KeyboardEvent): string|null => bemerkung.value = floskelPasteShortcut(event, bemerkung, floskeln)

</script>

<template>
    <div class="container">
        <h2 class="text-headline">{{ props.leistung.fach }} Fachbezogene Bemerkungen</h2>
        <h1 class="text-headline-xl text-primary">{{ props.leistung.name }}</h1>

        <SvwsUiTextareaInput
            v-model="computedBemerkung"
            @update:modelValue="bemerkung = $event"
            :disabled="props.readonly"
            ref="textareaContent"
            @keydown="onKeyDown"
        ></SvwsUiTextareaInput>

        <div class="buttons">
            <SvwsUiButton v-if="!readonly" @click="addSelected" :disabled="selectedFloskeln.length === 0">
                Zuweisen
            </SvwsUiButton>

            <SvwsUiButton v-if="!readonly" @click="save" :disabled="!isDirty">
                Speichern
            </SvwsUiButton>

            <SvwsUiButton @click="close" :type="isDirty ? 'danger' : 'secondary'">
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
                <SvwsUiTextInput type="search" v-model="searchTerm" placeholder="Suche"></SvwsUiTextInput>
            </template>

            <template #filter>
<!--                // TODO: Missing UI Component-->
               <SvwsUiMultiSelect
                        title="Niveau"
                        placeholder="Niveau"
                        v-model="niveauFilter"
                        :items="niveauOptions"
                        :item-text="item => item?.label || ''"
                        autocomplete
                        :removable="false"
                ></SvwsUiMultiSelect>
                <!-- breaks the whole filter if active but wasn't working with SvwsUiSelectInput anyway -->
                <!-- <SvwsUiMultiSelect
                        title="Jahrgang"
                        placeholder="Jahrgang"
                        v-model="jahrgangFilter"
                        :items="jahrgangOptions"
                        :item-text="item => item?.label || ''"
                        autocomplete
                        :removable="false"
                ></SvwsUiMultiSelect> -->
            </template>
        </SvwsUiDataTable>
    </div>
</template>

<style scoped>
    .container {
        @apply ui-p-6 ui-flex ui-flex-col ui-gap-6
    }

    .buttons {
        @apply ui-flex ui-justify-end ui-gap-3
    }
</style>
