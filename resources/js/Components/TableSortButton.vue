<script setup lang="ts">
    import { Ref, ref, provide } from 'vue'
    import { SortTableColumns } from '../types'
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
            <!-- todo: make the comparison neat -->
            <mdi-arrow-up-down class="sort-icon-inactive" v-if="presentColumn != props.sortBy"></mdi-arrow-up-down>
            <mdi-arrow-down-thick class="sort-icon" v-else-if="direction"></mdi-arrow-down-thick>
            <mdi-arrow-up-thick class="sort-icon" v-else-if="!direction"></mdi-arrow-up-thick>
        </SvwsUiIcon>
    </button>
</template>

<style scoped>
    
    .sort-icon {
        @apply ui-inline ui-gap-1.5 ui-items-center ui-justify-start
    }
    .sort-icon-inactive {
        @apply sort-icon ui-text-gray-300
    }
</style>
