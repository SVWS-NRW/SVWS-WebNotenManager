<script setup lang="ts">
    import { watch, reactive, VNodeRef, inject } from 'vue'
        import { usePage } from '@inertiajs/inertia-vue3'
    import axios from 'axios'
    import { Leistung } from '../Interfaces/Leistung'
    import { Schueler } from '../Interfaces/Schueler'

    import { SvwsUiTextInput } from '@svws-nrw/svws-ui'

    import { Payload, CellRef } from '../Helpers/tableNavigationHelper'

    interface EmitsOptions {
        (event: 'navigate', payload: Payload): void
    }

    let props = defineProps<{
        model: Leistung | Schueler,
        column: 'fs'|'fsu'|'gfs'|'gfsu',
        disabled: boolean,
        rowIndex: number,
        cellIndex: number,
    }>()

    const emit = defineEmits<EmitsOptions>()
    const cellRefs: any = inject('cellRefs')

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
    const navigate = (direction: string): void => emit('navigate', { direction: direction, rowIndex: props.rowIndex, cellIndex: props.cellIndex})        
    const setCellRefs = (element: CellRef): CellRef => cellRefs[`${props.rowIndex}-${props.cellIndex}`] = element
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
            :ref="(element: CellRef): CellRef => setCellRefs(element)"
        ></SvwsUiTextInput>
    </strong>
</template>
