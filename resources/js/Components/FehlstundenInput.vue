<script setup lang="ts"> // ok
    import { watch, computed, reactive } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3'
    import axios from 'axios'
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'
    import { Leistung } from '../Interfaces/Leistung'
    import {Schueler} from '../Interfaces/Schueler'

    const props = defineProps(['model', 'column'])

    let model = reactive<Leistung|Schueler>(props.model)
    let timeout: ReturnType<typeof setTimeout>
    let stored: number = props.model[props.column]

    const config = {
        'fs': 'api.fehlstunden.leistung.gesamt',
        'ufs': 'api.fehlstunden.leistung.unentschuldigt',
        'gfs': 'api.fehlstunden.schueler.gesamt',
        'gfsu': 'api.fehlstunden.schueler.gesamt_unentschuldigt',
    }

    watch((): any => model[props.column], (): void => {
        clearTimeout(timeout)

        timeout = setTimeout(() => saveFehlstunden(), 500)
    })

    const saveFehlstunden = () => axios
        .post(route(config[props.column], model), { value : model[props.column] })
        .then((): Number => stored = model[props.column])
        .catch((): Number => model[props.column] = stored)
</script>

<template>
    <SvwsUiTextInput v-model="model[props.column]" :headless="true"></SvwsUiTextInput>
</template>
