<script setup lang="ts"> // ok
    import { watch, computed, reactive } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3'
    import axios, { AxiosError, AxiosPromise, AxiosResponse } from 'axios'
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'
    import { Leistung } from '../Interfaces/Leistung'

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
        .post(route('api.noten', leistung), { note: leistung.note })
        .catch((error: AxiosError): AxiosResponse => leistung.note = error.response.data.note)

    const lowScore: ReturnType<typeof computed> = computed((): boolean => lowScoreArray.includes(leistung.note))
    const isDisabled = (): boolean => usePage().props.value.note_entry_disabled || props.disabled
</script>

<template>
    <span :class="{ 'low-score' : lowScore }" >
        <span v-if="isDisabled()">{{ leistung.note }}</span>
        <SvwsUiTextInput v-else v-model="leistung.note" :valid="!lowScore" :headless="true"></SvwsUiTextInput>
    </span>
</template>

<style scoped>
    .low-score {
        @apply ui-text-red-500 ui-font-bold
    }
</style>