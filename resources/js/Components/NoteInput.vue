<script setup lang="ts">
    import { watch, ref } from 'vue'
    import axios, { AxiosPromise } from 'axios'
    import { Leistung } from '@/Interfaces/Interface'
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'

    const props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
    }>()

    const lowScoreArray: Array<string> = ['6', '5-', '5', '5+', '4-']
    const note = ref(props.leistung.note)

    let debounce: ReturnType<typeof setTimeout>
    watch(note, (): void => {
        clearTimeout(debounce)
        debounce = setTimeout((): AxiosPromise => saveNote(), 500)
    })

    const saveNote = (): AxiosPromise => axios
        .post(route('api.noten', props.leistung), { note: note.value })
        .then((): string => props.leistung.note = note.value)
        .catch((): string => note.value = props.leistung.note)

    const valid: boolean = (): boolean => !lowScoreArray.includes(note.value)
</script>

<template>
    <strong  :class="{ 'low-score' : !valid() }">
        <span v-if="props.disabled">
            {{ note }}
        </span>

        <SvwsUiTextInput
            v-else
            v-model="note"
            :disabled="props.disabled"
            :valid="() => valid()"
        ></SvwsUiTextInput>
    </strong>
</template>

<style scoped>
    .low-score {
        @apply ui-text-red-500
    }
</style>
