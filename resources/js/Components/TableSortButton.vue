<script setup lang="ts">
//TODO: use provide and inject from/to Leistungsdatenuebersicht
    import { Ref, ref, provide } from 'vue'
    import { SortTableColumns } from '../types'
    import { SvwsUiIcon } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        sortRef: SortTableColumns,
        name: SortTableColumns,
    }>()

    const sortReferences: Ref<SortTableColumns> = ref(props.sortRef)
    const presentColumn: Ref<SortTableColumns> = ref(props.name);

    const sortTable = (newSortBy: SortTableColumns): void => {
        if (props.sortRef.sortBy == newSortBy.sortBy) {
            alert("first")
            sortReferences.value.direction = sortReferences.value.direction
        } else {
            alert(newSortBy.sortBy)
            sortReferences.value.direction = true
            sortReferences.value.sortBy = newSortBy.sortBy
        }
        clicked(sortReferences.value)
    }

    const emit = defineEmits(['clicked'])
    const clicked = (newSortRef: SortTableColumns): void => emit('clicked', newSortRef)

</script>

<template>
    <button @click="sortTable(presentColumn)">
        <slot></slot>
        {{ presentColumn.sortBy }}
        {{ presentColumn.direction }}
        <span class="column-name"></span>
        <SvwsUiIcon>
            <mdi-arrow-up-down class="sort-icon-inactive" v-if="presentColumn.sortBy != props.sortRef.sortBy"></mdi-arrow-up-down>
            <mdi-arrow-down-thick class="sort-icon" v-else-if="sortReferences.direction"></mdi-arrow-down-thick>
            <mdi-arrow-up-thick class="sort-icon" v-else-if="!sortReferences.direction"></mdi-arrow-up-thick>
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
