<template>
    <span v-if="props.disabled">
        {{ model }}
    </span>

    <SvwsUiTextInput
        v-else
        v-model="model"
        :disabled="props.disabled"
        @keydown.up.stop.prevent="navigate('up')"
        @keydown.down.stop.prevent="navigate('down')"
        @keydown.enter.stop.prevent="navigate('down')"
        :ref="(el: CellRef): CellRef => {element = el; setCellRefs(element, props.rowIndex); return el}"

    ></SvwsUiTextInput>
</template>


<script setup lang="ts">
    import {watch, ref, Ref} from 'vue';
    import axios, {AxiosPromise, AxiosResponse} from 'axios';
    import { Leistung, Schueler } from '@/Interfaces/Interface';
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui';
    import { CellRef, setCellRefs, navigateTable, selectItem } from '../Helpers/tableNavigationHelper'


    let props = defineProps<{
        model: Leistung | Schueler,
        column: 'fs'|'fsu'|'gfs'|'gfsu',
        disabled: boolean,
        rowIndex: number,
    }>();

    let element: CellRef = undefined
    
    const model: Ref<number|string> = ref(props.model[props.column]);

    let debounce: ReturnType<typeof setTimeout>;
    watch(model, (): void => {
        clearTimeout(debounce);
        debounce = setTimeout((): void => saveFehlstunden(), 500);
    });

    const saveFehlstunden = (): void => {
        if (isNaN(parseInt(model.value as string))) {
            model.value = '';
        }
        axios.post(route(`api.fehlstunden.${props.column}`, props.model), {value: model.value})
            .then((): number => props.model[props.column] = model.value)
            .catch((): number => model.value = props.model[props.column]);
    }

    const navigate = (direction: string): Promise<void> => navigateTable(direction, props.rowIndex, element)
</script>


<style scoped>
/**/
</style>