<script setup lang="ts"> // ok
    import { watch, reactive } from 'vue'
    import axios from 'axios'
    import { Leistung } from '../Interfaces/Leistung'
    import { Schueler } from '../Interfaces/Schueler'

    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'

    const props = defineProps<{
        model: Leistung|Schueler,
        column: 'fs'|'fsu'|'gfs'|'gfsu'
    }>()

    let model = reactive<Leistung|Schueler>(props.model)
    let timeout: ReturnType<typeof setTimeout>
    let stored: number = props.model[props.column]

    watch((): any => model[props.column], (): void => {
        clearTimeout(timeout)

        timeout = setTimeout(() => saveFehlstunden(), 500)
    })

    const saveFehlstunden = () => axios
        .post(route(`api.fehlstunden.${props.column}`, model), { value : model[props.column] })
        .then((): Number => stored = model[props.column])
        .catch((): Number => model[props.column] = stored)
</script>

<template>
    <strong>
        <SvwsUiTextInput v-model="model[props.column]" :headless="true"></SvwsUiTextInput>
    </strong>
</template>
