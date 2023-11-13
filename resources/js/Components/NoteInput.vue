<script setup lang="ts">
    import { watch, computed, reactive } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3'
    import axios, { AxiosError, AxiosPromise, AxiosResponse } from 'axios'
    import {Leistung} from "@/Interfaces/Interface"

    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'

    import { CellRef, setCellRefs, navigateTable, selectItem } from '../Helpers/tableNavigationHelper'

    let props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
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

    const lowScore: ReturnType<typeof computed> = computed((): boolean => lowScoreArray.includes(leistung.note))

    const saveNote = (): AxiosPromise => axios
        .post(route('api.noten', leistung), { note: leistung.note })
        .then((): string => stored = leistung.note)
        .catch((): string => leistung.note = stored)
</script>

<template>
    <strong :class="{ 'low-score' : lowScore }">
        <span v-if="props.disabled">{{ props.leistung.note }}</span>
        <SvwsUiTextInput
            v-else
            v-model="props.leistung.note"
            :disabled="props.disabled"
            :valid="() => !lowScore"
        ></SvwsUiTextInput>
    </strong>
</template>

<style scoped>
    .low-score {
        @apply ui-text-red-500
    }
</style>
