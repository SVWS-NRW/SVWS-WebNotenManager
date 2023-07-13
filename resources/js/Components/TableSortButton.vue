<script setup lang="ts">
    import {Ref, ref} from 'vue'
    import { SvwsUiIcon } from '@svws-nrw/svws-ui';

//TODO: if finally used, add names from other tables and check they are all here
//problem: we need string methods available here
//interface SortTableColumns { name: 'name' | 'klasse' | 'fach' | 'kurs' | 'fachleher' | 'note' | 'mahnung' | 'fs' | 'fsu' | 'fachbezogeneBemerkungen'}

    const props = defineProps<{
        sortBy: string,
        descDirection: boolean,
        columnName: string
    }>()

    const presentColumn: Ref<string> = ref(props.columnName);
    const direction = ref(props.descDirection);
    const sortByColumn: Ref<string> = ref(props.sortBy);

    const sortTable = (newSortBy: string): void => {
        if (props.sortBy == newSortBy) {
            direction.value = !direction.value
        } else {
            direction.value = true
            sortByColumn.value = newSortBy
        }
    clicked(newSortBy, direction.value)

    }

    const emit = defineEmits(['clicked'])
    const clicked = (clickedTable: string, newDirection: boolean): void => emit('clicked', clickedTable, newDirection)

</script>

<template>
    <button  @click="sortTable(presentColumn.toLowerCase())">
        <span class="column-name">{{ presentColumn }}</span>
        <SvwsUiIcon>
            <mdi-arrow-down-thick class="sort-icon" v-if="presentColumn.toLowerCase() == props.sortBy && direction == true"></mdi-arrow-down-thick>
            <mdi-arrow-up-thick class="sort-icon" v-else-if="presentColumn.toLowerCase() == props.sortBy && direction == false"></mdi-arrow-up-thick>
        </SvwsUiIcon>
    </button>
</template>

<style scoped>
    .sort-icon {
        @apply
        ui-inline ui-gap-1.5 ui-items-center ui-justify-start
    }
</style>
