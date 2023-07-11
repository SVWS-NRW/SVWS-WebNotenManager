<script setup lang="ts">
//todo: clear up what is needed here
//import {Leistung, Schueler} from '../types'
import {Ref, ref, toRef, watch} from 'vue'
import { SvwsUiIcon } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        //todo: clear up what is needed here
        table: string,
        //direction: boolean,
        columnName: string
    }>()


    
    //somehow use sthg like this?
/*     const direction = ref(true);
    const sortBy = ref(props.columnName); */

    const direction = ref(false);
    const sortedBy: Ref<string> = ref('name')

    const mySort = (columnName: string) => {
        //todo: direction hasn't been worked on yet
        if (props.table == columnName) {
            direction.value = !direction.value
            alert("same")
        } else {
            direction.value = true
            //cannot do this, 'cause props are readonly, should send it back to be SortBy.value on parent
            //props.table = columnName
            sortedBy.value = columnName
        }
        alert(sortedBy.value)
        alert(props.table)
    }

    const emit = defineEmits(['clicked'])
    const clicked = (): void => emit('clicked')


</script>

<template>
    <!-- TODO: emit to parent -->
    <!-- <button  @click="clicked" class=""> -->
    <button  @click="mySort(props.columnName)" class="">
        <SvwsUiIcon>
            <span class="column-name">{{ props.columnName }}</span>
            <mdi-arrow-down-thick class="sort-icon" v-if="direction"></mdi-arrow-down-thick>
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
