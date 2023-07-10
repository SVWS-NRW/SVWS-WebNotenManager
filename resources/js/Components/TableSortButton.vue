<script setup lang="ts">
//was noch?
    //import {Leistung, Schueler} from '../types'
import { SvwsUiIcon } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        //do i need table?
        table: string,
        direction: string,
        columnName: string,
    }>()

    const emit = defineEmits(['clicked'])
    const clicked = (): void => emit('clicked')
</script>

<template>
    <button  @click="clicked" class="">
        <SvwsUiIcon>
            <span class="column-name">{{ props.columnName }}</span>
            <mdi-arrow-down-thick v-if="props.columnName" class="sort-icon"></mdi-arrow-down-thick>
            <!-- todo: up or down, depending on order -->
            <!-- <mdi-arrow-up-thick v-else></mdi-arrow-up-thick> -->
        </SvwsUiIcon>
    </button>
</template>

<style scoped>

.column-name {
        @apply
        ui-inline
    }
     .sort-icon {
        @apply
        ui-inline ui-gap-1.5 ui-items-center ui-justify-start

    }
</style>
