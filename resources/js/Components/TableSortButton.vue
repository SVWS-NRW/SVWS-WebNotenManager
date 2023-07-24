<script setup lang="ts">
//TODO: use provide and inject from/to Leistungsdatenuebersicht
    import { Ref, ref, inject } from 'vue'
    import { SortTableColumns } from '../types'
    import { SvwsUiIcon } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        presentColumn: SortTableColumns,
    }>()

    const emit = defineEmits(['clicked'])
    const clicked = (newSortRef: SortTableColumns): void => emit('clicked', newSortRef)

    
    const sortRef: Ref<SortTableColumns> = inject('sortRef')
      
    const newSortReference: Ref<SortTableColumns> = ref(props.presentColumn);

    const sortTable = (newSortRef: SortTableColumns): void => {
        if (sortRef.value.sortBy == newSortRef.sortBy) {
            newSortRef.direction = !sortRef.value.direction
        } else {
            newSortRef.direction = true
        }
        clicked(newSortRef)
    }

</script>

<template>
    <button @click="sortTable(newSortReference)">
        <slot></slot>
        <span class="column-name"></span>
        <SvwsUiIcon>
            <mdi-arrow-up-down class="sort-icon-inactive" v-if="presentColumn.sortBy != sortRef.sortBy"></mdi-arrow-up-down>
            <mdi-arrow-down-thick class="sort-icon" v-else-if="sortRef.direction"></mdi-arrow-down-thick>
            <mdi-arrow-up-thick class="sort-icon" v-else-if="!sortRef.direction"></mdi-arrow-up-thick>
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
