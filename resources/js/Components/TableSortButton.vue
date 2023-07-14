<script setup lang="ts">
    import {Ref, ref} from 'vue'
    import {SortTableColumns} from '../types'
    import { SvwsUiIcon } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        sortBy: SortTableColumns,
        descDirection: boolean,
        displayName: string,
        dbName: SortTableColumns,
    }>()

    const emit = defineEmits(['clicked'])
    const clicked = (clickedTable: SortTableColumns, newDirection: boolean): void => emit('clicked', clickedTable, newDirection)

    const direction: Ref<boolean> = ref(props.descDirection);
    const sortByColumn: Ref<SortTableColumns> = ref(props.sortBy);
    const presentColumn: Ref<SortTableColumns> = ref(props.dbName);

    const sortTable = (newSortBy: SortTableColumns): void => {
        if (props.sortBy == newSortBy) {
            direction.value = !direction.value
        } else {
            direction.value = true
            sortByColumn.value = newSortBy
        }
        clicked(newSortBy, direction.value)
    }

</script>

<template>
    <button  @click="sortTable(presentColumn)">
        <span class="column-name">{{ props.displayName }}</span>
        <SvwsUiIcon>
            <mdi-arrow-down-thick class="sort-icon" v-if="presentColumn == props.sortBy && direction == true"></mdi-arrow-down-thick>
            <mdi-arrow-up-thick class="sort-icon" v-else-if="presentColumn == props.sortBy && direction == false"></mdi-arrow-up-thick>
        </SvwsUiIcon>
    </button>
</template>

<style scoped>
    .sort-icon {
        @apply
        ui-inline ui-gap-1.5 ui-items-center ui-justify-start
    }
</style>
