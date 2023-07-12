<script setup lang="ts">
//todo: clear up what is needed here
import {Ref, ref, toRef, watch} from 'vue'
import { SvwsUiIcon } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        //todo: clear up what is needed here
        sortBy: string,
        direction: boolean,
        columnName: string
    }>()

    const revertDirection = ref(false);
    const emit = defineEmits(['clicked'])
    //working version but without direction
    const clicked = (value): void => emit('clicked', value)


</script>

<template>
    <button  @click="clicked(props.columnName.toLowerCase())">
        <span class="column-name"> {{ props.columnName}} {{revertDirection }} </span>
        <SvwsUiIcon>
            <mdi-arrow-down-thick class="sort-icon" v-if="props.columnName.toLowerCase() == props.sortBy && revertDirection == false"></mdi-arrow-down-thick>
            <mdi-arrow-up-thick class="sort-icon" v-else-if="revertDirection == true"></mdi-arrow-up-thick>
        </SvwsUiIcon>
    </button>
</template>

<style scoped>
    .sort-icon {
        @apply
        ui-inline ui-gap-1.5 ui-items-center ui-justify-start

    }
</style>
