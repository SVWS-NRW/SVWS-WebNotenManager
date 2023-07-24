<script setup lang="ts">
    import { watch, reactive } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3'

    import axios from 'axios'
    import { Leistung } from '../Interfaces/Leistung'
    import { Schueler } from '../Interfaces/Schueler'
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'


    import { CellRef, setCellRefs, navigateTable } from '../Helpers/tableNavigationHelper'
 

    let props = defineProps<{
        model: Leistung | Schueler,
        column: 'fs'|'fsu'|'gfs'|'gfsu',
        disabled: boolean,
        rowIndex: number,

    }>()

    let element: CellRef = undefined
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


    const isDisabled = (): boolean => Boolean(usePage().props.value && usePage().props.value.note_entry_disabled) || props.disabled    
    const navigate = (direction: string): Promise<void> => navigateTable(direction, props.rowIndex, element)
</script>

<template>
    <strong>
        <span v-if="isDisabled()">{{ props.model[props.column] }}</span>

        <SvwsUiTextInput
            v-else 
            v-model="model[props.column]" 
            :headless="true"
            @keydown.up.stop.prevent="navigate('up')"
            @keydown.down.stop.prevent="navigate('down')"
            @keydown.enter.stop.prevent="navigate('down')"
            @keydown.left.stop.prevent="navigate('left')"
            @keydown.right.stop.prevent="navigate('right')"
            @keydown.tab.stop.prevent="navigate('right')"
            :ref="(el: CellRef): CellRef => {element = el; setCellRefs(element, props.rowIndex); return el}"
        ></SvwsUiTextInput>
    </strong>
</template>
