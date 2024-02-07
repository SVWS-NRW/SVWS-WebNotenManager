<template>
    <strong  :class="{ 'low-score' : !valid() }">
        <span v-if="props.disabled">
            {{ note }}
        </span>

        <SvwsUiTextInput
            v-else
            v-model="note"
            :disabled="props.disabled"
            :valid="() => valid()"
            style="font-weight: bold;"
            @keydown.up.stop.prevent="navigate('up')"
            @keydown.down.stop.prevent="navigate('down')"
            @keydown.enter.stop.prevent="navigate('down')"
            :ref="(el: CellRef): CellRef => {element = el; setCellRefs(element, props.rowIndex); return el}"
        ></SvwsUiTextInput>
    </strong>
</template>


<script setup lang="ts">
    import { watch, ref, Ref } from 'vue';
    import axios, { AxiosPromise } from 'axios';
    import { Leistung } from '@/Interfaces/Interface';
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui';
    import { CellRef, setCellRefs, navigateTable, selectItem } from '../Helpers/tableNavigationHelper'

    const props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
        rowIndex: number,
    }>();

    let element: CellRef = undefined;

    const lowScoreArray: Array<string> = ['6', '5-', '5', '5+', '4-'];
    const note: Ref<string | null> = ref(props.leistung.note);

    let debounce: ReturnType<typeof setTimeout>;
    watch(note, (): void => {
        clearTimeout(debounce);
        debounce = setTimeout((): Promise<string | null> => saveNote(), 500);
    })

    const saveNote = (): Promise<string | null> => axios
        .post(route('api.noten', props.leistung), { note: note.value })
        .then((): string | null => props.leistung.note = note.value)
        .catch((): string | null => {
            if (props.leistung.note === null) {
                return note.value = ""
            } else {
                return note.value = props.leistung.note
            }
        });

    const navigate = (direction: string): Promise<void> => navigateTable(direction, props.rowIndex, element)

    const valid = (): boolean => !lowScoreArray.includes(note.value as string);
</script>


<style scoped>
    .low-score {
        @apply text-red-500
    }
    
</style>
