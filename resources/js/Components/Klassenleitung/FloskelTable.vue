<script setup lang="ts">
    import {computed, onMounted, reactive} from "vue";
    import axios, {AxiosResponse} from "axios";

    const emit = defineEmits(['added'])

    type floskel = { gruppe: string, id: number, kuerzel: string, text: string }
    type floskelgruppe = { kuerzel: string, floskeln: floskel }
    type selected = { text: string, selected: boolean }

    let props = defineProps({
        floskelgruppe: String,
        floskelgruppen: Array,
    })

    const state = reactive({
        selected: <selected[]> [],
        search: <string> '',

        floskelgruppen: <floskelgruppe[]> props.floskelgruppen as Array<any>,
        floskelgruppe: <string> props.floskelgruppe,

    })

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
    );

    const searchFilter = (floskel: floskel): boolean => {
        if (state.search == '') return true
        return floskel.text.toLowerCase().includes(state.search.toLowerCase())
    }

    const select = (floskeln: Array<selected>): Array<selected> => state.selected = floskeln

    const add = (): void => {
        let bemerkung: string = state.selected.map((selected: selected): string => selected.text).join(' ');
        emit('added', bemerkung)
    }

    const type = computed((): string => state.selected.length > 0 ? 'primary' : 'secondary')

</script>

<template>
    <div class="flex flex-col gap-6">
    <h2 class="headline-4">Floskeln</h2>
    <hr class="border-gray">
    <div class="pb-0">
        <SvwsUiTextInput type="search" v-model="state.search" placeholder="Suche"></SvwsUiTextInput>
    </div>

    <SvwsUiNewTable :data="computedFloskeln" :columns="columns" selectionMode="multiple" :footer="true" v-on:update:modelValue="select">
        <template #footer>
            <SvwsUiButton @click="add" :type="type">Zuweisen</SvwsUiButton>
        </template>
    </SvwsUiNewTable>
    </div>
</template>

<style scoped>
/**/
</style>
