<script setup lang="ts">
    import { watch, computed, reactive } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3'
    import axios, { AxiosError, AxiosPromise, AxiosResponse } from 'axios'
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'
    import { Leistung } from '../../Interfaces/Leistung'

    const props = defineProps(['leistung', 'disabled'])

    let leistung = reactive<Leistung>(props.leistung)
    let timeout: ReturnType<typeof setTimeout>

    let lowScoreArray: Array<string> = [
        '6', '5-', '5', '5+', '4-',
    ]

    watch((): string => leistung.note, (): void => {
        clearTimeout(timeout)
        timeout = setTimeout((): AxiosPromise => saveNote(), 500)
    })

    const saveNote = (): AxiosPromise => axios
        .post(route('set_noten', leistung), { note: leistung.note })
        .then((response: AxiosResponse): AxiosResponse => leistung.note = response.data.note)
        .catch((error: AxiosError): AxiosResponse => leistung.note = error.response.data.note)

    const lowScore: ReturnType<typeof computed> = computed((): boolean => lowScoreArray.includes(leistung.note))
    const isDisabled = (): boolean => usePage().props.value.note_entry_disabled || props.disabled
</script>

<template>
    <strong v-if="isDisabled()" :class="{ 'low-score' : lowScore }">{{ leistung.note }}</strong>
    <SvwsUiTextInput v-else v-model="leistung.note" :valid="!lowScore"></SvwsUiTextInput>
</template>

<style scoped>
    .low-score {
        @apply ui-text-red-500 ui-font-bold
    }
</style>