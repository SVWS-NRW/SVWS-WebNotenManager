<script setup lang="ts">
//todo: clear up what is needed here
//import {Leistung, Schueler} from '../types'
import {Ref, ref, toRef, watch} from 'vue'
import { SvwsUiIcon } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        //todo: clear up what is needed here
        sortBy: string,
        direction: boolean,
        columnName: string
    }>()


    
    //testing here
    const direction = ref(true);

    const emit = defineEmits(['clicked'])
    const clicked = (value): void => emit('clicked', value)    
</script>

<template>
    <!-- TODO: emit to parent -->
    <!-- <button  @click="clicked" class=""> -->
    <!-- <button  @click="mySort(props.columnName)" class=""> -->
    <button  @click="clicked(props.columnName.toLowerCase())">
        <span class="column-name"> {{ props.columnName}} </span>
        <SvwsUiIcon>
            <mdi-arrow-down-thick class="sort-icon" v-if="props.columnName.toLowerCase() == props.sortBy"></mdi-arrow-down-thick>
        </SvwsUiIcon>
    </button>
</template>

<style scoped>
    .sort-icon {
        @apply
        ui-inline ui-gap-1.5 ui-items-center ui-justify-start

    }
</style>
