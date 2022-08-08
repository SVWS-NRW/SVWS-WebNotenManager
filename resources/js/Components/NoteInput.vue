<script setup lang="ts">
    import { watch, computed, reactive } from 'vue'
    import { useStore } from '../store'
    import axios, {AxiosError, AxiosResponse} from 'axios'

    const store = useStore()
    const props = defineProps(['leistung'])
    let leistung = reactive(props.leistung)
    let timeout
    let lowScoreArray = ['6', '5-', '5', '5+', '4-']

    watch((): void => leistung.note, (): void => {
        clearTimeout(timeout)
        timeout = setTimeout(() => saveNote(), 500)
    })

    const saveNote = (): Promise<void> => axios
        .post(route('set_noten', leistung), { note: leistung.note })
        .then((response: AxiosResponse) => leistung.note = response.data.note)
        .catch((error: AxiosError) => leistung.note = error.response.data.note)

    const lowScore = computed((): boolean => lowScoreArray.includes(leistung.note))
</script>

<template>
    <SvwsUiTextInput v-model="leistung.note" :valid="!lowScore"></SvwsUiTextInput>
</template>
