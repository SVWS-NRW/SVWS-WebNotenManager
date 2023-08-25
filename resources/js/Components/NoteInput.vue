<script setup lang="ts">
    import { watch, computed, reactive } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3'
    import axios, { AxiosError, AxiosPromise, AxiosResponse } from 'axios'
    import { Leistung } from '../types'

    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'

    import { CellRef, setCellRefs, navigateTable, selectItem } from '../Helpers/tableNavigationHelper'

    let props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
        rowIndex: number,
    }>()

    let element: CellRef = undefined

    let leistung = reactive<Leistung>(props.leistung)
    let timeout: ReturnType<typeof setTimeout>

    let lowScoreArray: Array<string> = [
        '6', '5-', '5', '5+', '4-',
    ]

    let stored: number = props.leistung.note

    watch((): string => leistung.note, (): void => {
        clearTimeout(timeout)
        timeout = setTimeout((): AxiosPromise => saveNote(), 500)
    })


    const saveNote = (): AxiosPromise => axios
        .post(route('api.noten', leistung), { note: leistung.note })
        .then((): string => stored = leistung.note)
        .catch((): string => leistung.note = stored)

    const lowScore: ReturnType<typeof computed> = computed((): boolean => lowScoreArray.includes(leistung.note))

    const isDisabled = (): boolean => Boolean(usePage().props.value && usePage().props.value.note_entry_disabled) || props.disabled

    const navigate = (direction: string): Promise<void> => navigateTable(direction, props.rowIndex, element)
    
    const selectClickedItem = (event: MouseEvent): void => selectItem(event) 
</script>

<template>
    <strong :class="{ 'low-score' : lowScore }" >
        <span v-if="isDisabled()">{{ props.leistung.note }}</span>
        <SvwsUiTextInput
            :disabled="isDisabled()"
            v-else
            v-model="props.leistung.note"
            :valid="!lowScore"
            :headless="true"
            @click="selectClickedItem($event)"
            @keydown.up.stop.prevent="navigate('up')"
            @keydown.down.stop.prevent="navigate('down')"
            @keydown.enter.stop.prevent="navigate('down')"
            @keydown.left.stop.prevent="navigate('left')"
            @keydown.right.stop.prevent="navigate('right')"
            @keydown.tab.stop.prevent="navigate('right')"
            :ref="(el: CellRef): CellRef => {element = el; setCellRefs(element, props.rowIndex); return el}"
        ></SvwsUiTextInput>
    </strong>
</template>

<style scoped>
    .low-score {
        @apply ui-text-red-500
    }
</style>