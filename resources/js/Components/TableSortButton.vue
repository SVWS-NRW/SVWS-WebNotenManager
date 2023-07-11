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
    const sortBy: Ref<'name' | 'klasse'> = ref('name')

    const mySort = (name: 'klasse' | 'name') => {
          direction.value = !direction.value;
          alert(direction.value)
        if (sortBy.value == name) {
            direction.value = !direction.value
        } else {
            direction.value = true
            sortBy.value = name
        }
    }

    const emit = defineEmits(['clicked'])
    const clicked = (): void => emit('clicked')


</script>

<template>
    <!-- <button  @click="clicked" class=""> -->
    <button  @click="mySort('fach')" class="">
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
