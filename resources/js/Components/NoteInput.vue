<script setup lang="ts">
    import { watch, computed, reactive, inject, VNodeRef } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3'
    import axios, { AxiosError, AxiosPromise, AxiosResponse } from 'axios'
    import { Leistung } from '../types'
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'
    import { Payload, CellRef } from '../Helpers/tableNavigationHelper'

    interface EmitsOptions {
        (event: 'navigate', payload: Payload): void
    }

    let props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
        rowIndex: number,
        cellIndex: number,
    }>()

    const emit = defineEmits<EmitsOptions>()
    const cellRefs: any = inject('cellRefs')

    let leistung = reactive<Leistung>(props.leistung)
    let debounce: ReturnType<typeof setTimeout>
    let lowScoreArray: Array<string | null> = ['6', '5-', '5', '5+', '4-']

    watch((): string | null => leistung.note, (): void => {
        clearTimeout(debounce)
        debounce = setTimeout((): AxiosPromise => saveNote(), 500)
    })

    const lowScore: ReturnType<typeof computed> = computed((): boolean => 
        lowScoreArray.includes(leistung.note)
    )

    const saveNote = (): AxiosPromise => axios
        .post(route('api.noten', leistung), { note: leistung.note })
        .catch((error: AxiosError): AxiosResponse => leistung.note = error.response?.data.note)

    const isDisabled = (): boolean => Boolean(usePage().props.value && usePage().props.value.note_entry_disabled) || props.disabled
    const navigate = (direction: string): void => emit('navigate', { direction: direction, rowIndex: props.rowIndex, cellIndex: props.cellIndex})        
    const setCellRefs = (el: CellRef): CellRef => cellRefs[`${props.rowIndex}-${props.cellIndex}`] = el
</script>

<template>
    <strong :class="{ 'low-score' : lowScore }">
        <span v-if="isDisabled()">{{ leistung.note }}</span>

        <SvwsUiTextInput
            v-else
            v-model="leistung.note"
            :valid="!lowScore"
            :headless="true"
            @keydown.up.stop.prevent="navigate('up')"
            @keydown.down.stop.prevent="navigate('down')"
            @keydown.enter.stop.prevent="navigate('down')"
            @keydown.left.stop.prevent="navigate('left')"
            @keydown.right.stop.prevent="navigate('right')"
            @keydown.tab.stop.prevent="navigate('right')"
            :ref="(el: CellRef): CellRef => setCellRefs(el)"
        ></SvwsUiTextInput>
    </strong>
</template>

<style scoped>
    .low-score {
        @apply ui-text-red-500
    }
</style>