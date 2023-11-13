<script setup lang="ts">
    import { watch, reactive } from 'vue'
    import axios from 'axios'
    import { Leistung, Schueler } from "@/Interfaces/Interface";

    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'

    let props = defineProps<{
        model: Leistung | Schueler,
        column: 'fs'|'fsu'|'gfs'|'gfsu',
        disabled: boolean,
    }>()

    let model = reactive<Leistung|Schueler>(props.model)
    let debounce: ReturnType<typeof setTimeout>
    let stored: number = props.model[props.column]

    watch((): any => model[props.column], (): void => {
        clearTimeout(debounce)
        debounce = setTimeout(() => saveFehlstunden(), 500)
    })

    const saveFehlstunden = () => axios
        .post(route(`api.fehlstunden.${props.column}`, model), { value : model[props.column] })
        .then((): Number => stored = model[props.column])
        .catch((): Number => model[props.column] = stored)
</script>

<template>
    <span v-if="props.disabled">
        {{ model[props.column] }}
    </span>

    <SvwsUiTextInput
        v-else
        v-model="model[props.column]"
        :headless="true"
        @click="selectClickedItem($event)"
    ></SvwsUiTextInput>
</template>
