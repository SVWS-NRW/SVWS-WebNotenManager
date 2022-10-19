<script setup lang="ts">
    import {computed, onMounted, reactive} from "vue";
    import axios, {AxiosResponse} from "axios";

    const emit = defineEmits(['added'])

    type floskel = {gruppe: string, id: number, kuerzel: string, text: string}
    type selected = {text: string, selected: boolean}
    type column = {key: string, label: string, sortable: boolean}

    let props = defineProps({floskeln: Array})

    const state = reactive({
        selected: <selected[]> [],
        search: <string> '',
        floskeln: <floskel[]> props.floskeln as Array<any>,
        filterValues: {
            'niveau': [],
            'jahrgaenge': [],
        },
    });

    const columns = <column[]>[
        { key: 'kuerzel', label: 'Kürzel', sortable: true },
        { key: 'fach_id', label: 'Fach', sortable: true },
        { key: 'jahrgang_id', label: 'Jahrgang', sortable: true },
        { key: 'text', label: 'Text', sortable: true },
        { key: 'niveau', label: 'Niveau', sortable: true },
    ]

    onMounted((): void => {
        axios.get(route('get_fachbezogene_floskeln_filters'))
            .then((response: AxiosResponse): AxiosResponse => state.filterValues = response.data)
    })

    const computedFloskeln = computed(() => props.floskeln.filter((floskel: floskel) =>
        searchFilter(floskel)
        && tableFilter(floskel, 'niveau')
        && tableFilter(floskel, 'jahrgang_id')
    ))

    const tableFilter = (floskel: floskel, column: string, withOnlyEmptyOption: boolean = false) => {
        if (withOnlyEmptyOption && [null, ''].includes(filters[column])) return floskel[column] == null
        if (filters[column] == 0) return true
        return floskel[column] == filters[column]
    }

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

    const filters = reactive({
        niveau: <Number|string> 0,
        jahrgang_id: <Number|string> 0,
    })
</script>

<template>
    <div class="flex flex-col gap-6">
    <h2 class="headline-4">Floskeln</h2>
    <hr class="border-gray">
    <div class="pb-0">
        <div class="grid grid-cols-3 gap-3">
            <SvwsUiTextInput type="search" v-model="state.search" placeholder="Suche"></SvwsUiTextInput>
            <SvwsUiSelectInput placeholder="Niveau" v-model="filters.niveau" @update:value="(niveau: Number) => filters.niveau = niveau" :options="state.filterValues.niveau"></SvwsUiSelectInput>
            <SvwsUiSelectInput placeholder="Jahrgang" v-model="filters.jahrgang_id" @update:value="(jahrgang: Number) => filters.jahrgang_id = jahrgang" :options="state.filterValues.jahrgaenge"></SvwsUiSelectInput>
        </div>
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
