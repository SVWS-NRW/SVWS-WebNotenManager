<template>
    <strong  :class="{ 'low-score' : !valid() }">
        <span style="padding: 0px 0.7em; font-weight: bold;" v-if="props.disabled">
            {{ note }}
        </span>
        <SvwsUiTextInput
            v-else
            v-model="note"
            :disabled="props.disabled"
            :valid="() => valid()"
            style="font-weight: bold;"
            :ref="(el) => updateItemRefs(rowIndex, el as Element, 'itemRefsNoteInput')"
            @click="$event.target.select()"
            @keydown.up.stop.prevent="navigate('previous', props.rowIndex)"
            @keydown.down.stop.prevent="navigate('next', props.rowIndex)"
            @keydown.enter.stop.prevent="navigate('next', props.rowIndex)"
        ></SvwsUiTextInput>
    </strong>
</template>


<script setup lang="ts">
    import { watch, ref, Ref, VNodeRef, onMounted } from 'vue';
    import axios, { AxiosPromise } from 'axios';
    import { Leistung } from '@/Interfaces/Interface';
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui';
    //TODO: remove if finally unused
    import { CellRef, setCellRefs, navigateTable, selectItem } from '../Helpers/tableNavigationHelper'

    const props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
        rowIndex: number,
    }>();

    const emit = defineEmits(['navigated','updatedItemRefs'])

    const navigated = ( direction: string, rowIndex: number, itemRefsNoteInputName: string) : void => emit("navigated", direction, rowIndex, itemRefsNoteInputName)

    //corresponding itemRefs map in the parent gets a new pair every time the NoteInput "itemRefsNoteInputName" is uploaded
    const updateItemRefs = (rowIndex: number, el: Element, itemRefsNoteInputName: string): void => {
        emit("updatedItemRefs", rowIndex, el, itemRefsNoteInputName)
    }

    const navigate = (direction: string, rowIndex: number): void => {
        navigated(direction, rowIndex, "itemRefsNoteInput");
    }

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

    const valid = (): boolean => !lowScoreArray.includes(note.value as string);
</script>


<style scoped>
    .low-score {
        @apply text-red-500
    }
    
</style>
