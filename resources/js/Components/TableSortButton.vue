<script setup lang="ts">
    //todo: clear up what is needed here
    import {Ref, ref, toRef, watch} from 'vue'
    import { SvwsUiIcon } from '@svws-nrw/svws-ui';

// problem: we need string methods available here
//TODO: if finally used, add names from other tables and check they are all here
//interface SortTableColumns { name: 'name' | 'klasse' | 'fach' | 'kurs' | 'fachleher' | 'note' | 'mahnung' | 'fs' | 'fsu' | 'fachbezogeneBemerkungen'}

    const props = defineProps<{
        //todo: clear up what is needed here
        sortBy: string,
        descDirection: boolean,
        columnName: string
    }>()

    const presentColumn: Ref<string> = ref(props.columnName);
    const originalDirection = ref(props.descDirection);
    const sortByColumn: Ref<string> = ref(props.sortBy);

    const sortTable = (name: string) => {
        if (props.sortBy == name) {
            originalDirection.value = !originalDirection.value
        } else {
            originalDirection.value = true
            props.sortBy = name
        }
    }
    
    const emit = defineEmits(['clicked'])
    const clicked = (value: string): void => emit('clicked', value, sortTable(value))

</script>

<template>
    <button  @click="clicked(presentColumn.toLowerCase())">
        <span class="column-name">{{ presentColumn }}</span>
        <SvwsUiIcon>
            <mdi-arrow-down-thick class="sort-icon" v-if="presentColumn.toLowerCase() == props.sortBy && originalDirection == true"></mdi-arrow-down-thick>
            <mdi-arrow-up-thick class="sort-icon" v-else-if="originalDirection == false"></mdi-arrow-up-thick>
        </SvwsUiIcon>
    </button>
</template>

<style scoped>
    .sort-icon {
        @apply
        ui-inline ui-gap-1.5 ui-items-center ui-justify-start

    }
</style>
