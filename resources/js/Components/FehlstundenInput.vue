<script setup lang="ts"> // ok
    import { watch, computed, reactive } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3'
    import axios from 'axios'
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'
    import { Leistung } from '../Interfaces/Leistung'

    const props = defineProps(['leistung', 'column'])

    let leistung = reactive<Leistung>(props.leistung)
    let timeout: ReturnType<typeof setTimeout>
    let stored: number = props.leistung[props.column]

    const config = {
        'fs': 'api.fehlstunden.leistung.gesamt',
        'ufs': 'api.fehlstunden.leistung.unentschuldigt',
    }

    watch((): any => leistung[props.column], (): void => {
        clearTimeout(timeout)

        timeout = setTimeout(() => saveFehlstunden(), 500)
    })

    const saveFehlstunden = () => axios
        .post(route(config[props.column], leistung), { value : leistung[props.column] })
        .then((): Number => stored = leistung[props.column])
        .catch((): Number => leistung[props.column] = stored)
</script>

<template>
    <SvwsUiTextInput v-model="leistung[props.column]" :headless="true"></SvwsUiTextInput>
</template>
