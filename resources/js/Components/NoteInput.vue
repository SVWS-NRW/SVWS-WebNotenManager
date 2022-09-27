<script setup lang="ts">
    import {watch, computed, reactive} from 'vue'
    import {useStore} from '../store'

    import axios, {AxiosError, AxiosPromise, AxiosResponse} from 'axios'


    const store = useStore()
    const props = defineProps(['leistung'])
    let leistung = reactive(props.leistung)
    let timeout
    let lowScoreArray = ['6', '5-', '5', '5+', '4-']

    watch((): void => leistung.note, (): void => {
        clearTimeout(timeout)
        timeout = setTimeout(() => saveNote(), 500)
    })

    const saveNote = (): AxiosPromise => axios
        .post(route('set_noten', leistung), { note: leistung.note })
        .then((response: AxiosResponse): AxiosResponse => leistung.note = response.data.note)
        .catch((error: AxiosError): AxiosResponse => leistung.note = error.response.data.note)

    const lowScore = computed((): boolean => lowScoreArray.includes(leistung.note))
</script>

<template>
    <span :class="{'bg-red-500' : lowScore}">
        <SvwsUiTextInput v-model="leistung.note" :valid="!lowScore"></SvwsUiTextInput>
    </span>
</template>

<style scoped>
    .text-input-component {
        max-width: 48px !important;
    }
</style>