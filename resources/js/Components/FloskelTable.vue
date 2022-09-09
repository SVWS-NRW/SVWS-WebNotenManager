<script setup lang="ts">
    import { computed, reactive } from "vue";

    const emit = defineEmits(['added'])

    type columns = { id: string, title: string, sortable: boolean };
    type floskel = { gruppe: string, id: number, kuerzel: string, text: string };
    type floskelgruppe = { kuerzel: string, floskeln: floskel };
    type selected = { data: floskel, selected: boolean };

    let props = defineProps({
        floskelgruppe: String,
        floskelgruppen: Array,
    })

    const state = reactive({
        selected: <selected[]> [],
        search: <string> '',
        floskelgruppen: <floskelgruppe[]> props.floskelgruppen,
        floskelgruppe: <string> props.floskelgruppe,
        columns: <columns[]> [
            { id: 'kuerzel', title: 'Kürzel', sortable: true },
            { id: 'text', title: 'Text', sortable: true },
        ],
    });

    const computedFloskeln = computed(() =>
         state.floskelgruppen
             .find(floskelgruppe => floskelgruppe.kuerzel == state.floskelgruppe)
             .floskeln
             .filter((floskel:floskel): boolean => searchFilter(floskel))
    );

    const searchFilter = (floskel: floskel): boolean => {
        if (state.search == '') return true
        return floskel.text.toLowerCase().includes(state.search.toLowerCase())
    }

    const select = (floskeln: Array<selected>) => state.selected = floskeln

    const add = (): void => {
        let bemerkung: string = state.selected.map((selected: selected): string => selected.data.text).join(' ');
        emit('added', bemerkung)
        // TODO: clear floskeln from the table
    }

    const type = computed(() => state.selected.length > 0 ? 'primary' : 'secondary')
</script>

<template>
    <div class="flex flex-col gap-6">
        <h2 class="svws-ui-headline-4 svws-ui-text-black">Floskeln</h2>
        <hr class="svws-ui-border-gray">
        <SvwsUiTextInput type="search" v-model="state.search" placeholder="Suche"></SvwsUiTextInput>
        <div class="h-full overflow-y-scroll">
            <SvwsUiTable :cols="state.columns" :footer="true" :multiSelect="true" :rows="computedFloskeln" v-on:update:selectedItems="select">
                <template v-slot:footer>
                    <SvwsUiButton @click="add" :type="type">Zuweisen</SvwsUiButton>
                </template>
            </SvwsUiTable>
        </div>
    </div>
</template>

<style scoped>
/**/
</style>
