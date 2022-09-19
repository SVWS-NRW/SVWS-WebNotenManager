<script setup lang="ts">
    import { computed, reactive } from "vue";

    const emit = defineEmits(['added'])

<<<<<<< HEAD
    type columns = { id: string, title: string, sortable: boolean };
    type floskel = { gruppe: string, id: number, kuerzel: string, text: string };
    type floskelgruppe = { kuerzel: string, floskeln: floskel };
    type selected = { data: floskel, selected: boolean };
=======
    type floskel = { gruppe: string, id: number, kuerzel: string, text: string }
    type floskelgruppe = { kuerzel: string, floskeln: floskel }
    type selected = { text: string, selected: boolean }
>>>>>>> develop

    let props = defineProps({
        floskelgruppe: String,
        floskelgruppen: Array,
    })

    const state = reactive({
        selected: <selected[]> [],
        search: <string> '',
<<<<<<< HEAD
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
=======
        floskelgruppen: <floskelgruppe[]> props.floskelgruppen as Array<any>,
        floskelgruppe: <string> props.floskelgruppe,
    });

    const columns = <{ key: string, label: string, sortable: boolean }[]>[
        { key: 'id', label: 'id', sortable: true },
        { key: 'kuerzel', label: 'Kürzel', sortable: true },
        { key: 'text', label: 'Text', sortable: true },
    ]

    const computedFloskeln = computed((): void =>
         state.floskelgruppen
             .find((floskelgruppe: floskelgruppe): boolean => floskelgruppe.kuerzel == state.floskelgruppe)
             .floskeln
             .filter((floskel: floskel): boolean => searchFilter(floskel))
>>>>>>> develop
    );

    const searchFilter = (floskel: floskel): boolean => {
        if (state.search == '') return true
        return floskel.text.toLowerCase().includes(state.search.toLowerCase())
    }

<<<<<<< HEAD
    const select = (floskeln: Array<selected>) => state.selected = floskeln

    const add = (): void => {
        let bemerkung: string = state.selected.map((selected: selected): string => selected.data.text).join(' ');
        emit('added', bemerkung)
        // TODO: clear floskeln from the table
    }

    const type = computed(() => state.selected.length > 0 ? 'primary' : 'secondary')
=======
    const select = (floskeln: Array<selected>): Array<selected> => state.selected = floskeln

    const add = (): void => {
        let bemerkung: string = state.selected.map((selected: selected): string => selected.text).join(' ');
        emit('added', bemerkung)
    }

    const type = computed((): string => state.selected.length > 0 ? 'primary' : 'secondary')
>>>>>>> develop
</script>

<template>
    <div class="flex flex-col gap-6">
<<<<<<< HEAD
        <h2 class="svws-ui-headline-4 svws-ui-text-black">Floskeln</h2>
        <hr class="svws-ui-border-gray">
        <SvwsUiTextInput type="search" v-model="state.search" placeholder="Suche"></SvwsUiTextInput>
        <div class="h-full overflow-y-scroll">
            <SvwsUiTable :cols="state.columns" :footer="true" :multiSelect="true" :rows="computedFloskeln" v-on:update:selectedItems="select">
                <template v-slot:footer>
                    <SvwsUiButton @click="add" :type="type">Zuweisen</SvwsUiButton>
                </template>
            </SvwsUiTable>
=======
        <h2 class="headline-4">Floskeln</h2>
        <hr class="border-gray">
        <div class="pb-0">
            <SvwsUiTextInput type="search" v-model="state.search" placeholder="Suche"></SvwsUiTextInput>
        </div>
        <div class="h-full overflow-y-scroll">
            <SvwsUiNewTable :data="computedFloskeln" :columns="columns" selectionMode="multiple" :footer="true" v-on:update:modelValue="select">
                <template #footer>
                    <SvwsUiButton @click="add" :type="type">Zuweisen</SvwsUiButton>
                </template>
            </SvwsUiNewTable>
>>>>>>> develop
        </div>
    </div>
</template>

<style scoped>
/**/
</style>
