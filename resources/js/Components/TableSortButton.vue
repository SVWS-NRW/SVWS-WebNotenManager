<script setup lang="ts">
    import { Ref, ref, inject } from 'vue'
    import { SortTableColumns } from '../types'

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

    const updateIconColor = (directionDesc: boolean): string => {
        return sortRef.value.sortBy == newSortReference.value.sortBy && directionDesc != sortRef.value.direction ? '#329cd5' : 'silver'
    }

</script>

<template>
    <button @click="sortTable(newSortReference)" class="sorting-button"
        :class="{ 'sort-icon__active': sortRef.sortBy == newSortReference.sortBy }">
        <slot></slot>
        <span class="column-name"></span>
        <span class="icon">
            <svg viewBox="0 0 24 24" width="1.2em" height="1.2em" class="sort-icon-up">
                <path :fill="updateIconColor(false)"
                    d="m11.95 7.95l-1.414 1.414L8 6.828V20H6V6.828L3.466 9.364L2.05 7.95L7 3l4.95">
                </path>
            </svg>
            <svg viewBox="0 0 24 24" width="1.2em" height="1.2em" transform="rotate(180)" class="sort-icon-down">
                <path :fill="updateIconColor(true)"
                    d="m11.95 7.95l-1.414 1.414L8 6.828V20H6V6.828L3.466 9.364L2.05 7.95L7 3l4.95">
                </path>
            </svg>
        </span>
    </button>
</template>

<style scoped>
    .sorting-button {
        @apply ui-pl-1 hover:ui-bg-gray-100 ui-rounded-md hover:ui-p-1
    }

    .sort-icon {
        @apply ui-inline ui-gap-1.5 ui-items-center ui-justify-start
    }

    .sort-icon-up {
        @apply sort-icon ui-pl-1
    }

    .sort-icon-down {
        @apply ui-inline ui-pl-1 -ui-ml-3
    }

    .sort-icon__active {
        @apply ui-text-sortingblue ui-bg-blue-200/10 hover:ui-bg-blue-200/10 ui-p-1
    }
</style>
