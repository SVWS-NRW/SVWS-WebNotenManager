<script setup lang="ts">
import {computed, onMounted, reactive, watch} from 'vue'
    import { useStore } from '../store'
    import Menubar from '../Components/Menubar.vue'
    import TopMenu from "../Components/TopMenu.vue"
    import BemerkungenIndicator from "../Components/Klassenleitung/BemerkungenIndicator.vue"
    import FloskelnMenu from "../Components/Klassenleitung/FloskelnMenu.vue"
    import axios from 'axios'

    type schueler = { ext_id: Number, vorname: string, nachname: string, asv: string|null, aue: string|null, zb: string|null }
    type column = { id: string, title: string, sortable: boolean, visible: boolean }

type floskel = { gruppe: string, id: number, kuerzel: string, text: string };
type floskelgruppe = { kuerzel: string, floskeln: floskel };

    const store = useStore();

    let props = defineProps({
        auth: Object,
    })

    let state = reactive({
        schueler: <schueler[]> [],
        selected: <{schueler: schueler, floskelgruppe: String}|null>null,
        floskelgruppen: <floskelgruppe[]> [],
    })

    let sorting = reactive({
        column: <string>'',
        asc: <boolean>true,
    })

    const columns = <column[]>[
        {id: 'ext_id', title: 'ID', sortable: true, visible: true},
        {id: 'vorname', title: 'Vorname', sortable: true, visible: true},
        {id: 'nachname', title: 'Nachname', sortable: true, visible: true},
        {id: 'asv', title: 'Asv', sortable: false, visible: true},
        {id: 'aue', title: 'Aue', sortable: false, visible: true},
        {id: 'zb', title: 'Zb', sortable: false, visible: true},
    ]

    onMounted(() => {
        fetchSchueler()
        fetchFloskelGruppen()
    })

    const fetchSchueler = () => axios.get(route('get_schueler')).then(response => state.schueler = response.data)
    const fetchFloskelGruppen = () => axios.get(route('get_floskeln')).then(response => state.floskelgruppen = response.data)

    watch (sorting, () => executeSort());

    const changeSort = (column: column): void => {
        if (column.sortable) {
            sorting.asc = column.id === sorting.column ? !sorting.asc : true
            sorting.column = column.id
        }
    }

    const executeSort = (): void|number => {
        if (sorting.column == '') return

        const getSchuelerColumn = (schueler: schueler): string|null => sorting.column
            .split('.')
            .reduce((value: Array<string>, entry: string) => value && value[entry], schueler)

        state.schueler.sort((left: schueler, right: schueler) => {
            let a: string|null = getSchuelerColumn(left)
            let b: string|null = getSchuelerColumn(right)

            if (a === null) return 1
            if (b === null) return -1
            if (!sorting.asc) [a, b] = [b, a]

            return a.toString().localeCompare(b.toString(),"de-DE")
        });
    }

    const openFloskelMenu = (index: number, floskelgruppe: String) => state.selected = {
        schueler: state.schueler[index], floskelgruppe: floskelgruppe
    }

    const closeFloskelMenu = () => state.selected = null
    const updateSchueler = () => fetchSchueler()
</script>

<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
                <Menubar :auth="props.auth" />
            </template>

            <template #main>
                <div class="relative flex flex-col w-full h-screen">
                    <TopMenu headline="Klassenleitung"></TopMenu>
                    <div class="min-h-0 flex-1 overflow-auto z-10 shadow-[0_-1px_0px_0px] shadow-zinc-300 dark:shadow-zinc-600">
                        <table class="w-full relative">
                            <thead class="sticky top-0 left-0 z-10 bg-white dark:bg-zinc-900">
                                <tr>
                                    <th v-for="column in columns" :key="column.id" v-show="column.visible" @click="changeSort(column)" class="border-x border-zinc-300 dark:border-zinc-600">
                                        <div class="py-2 px-3 flex items-center gap-2">
                                            <span class="text-black dark:text-zinc-300 uppercase font-bold text-sm">
                                                {{ column.title }}
                                            </span>
                                            <div class="cursor-pointer" v-if="column.sortable">
                                                <svws-ui-icon v-show="column.sortable && column.id !== sorting.column">
                                                    <span class="sr-only">Sortieren</span>
                                                    <svg fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4 fill-zinc-400 dark:fill-zinc-600">
                                                        <path fill="none" d="M0 0h24v24H0z"/>
                                                        <path d="M13 16.172l5.364-5.364 1.414 1.414L12 20l-7.778-7.778 1.414-1.414L11 16.172V4h2v12.172z"/>
                                                    </svg>
                                                </svws-ui-icon>
                                                <svws-ui-icon v-show="column.sortable && sorting.asc && column.id === sorting.column">
                                                    <span class="sr-only">Aufsteigend</span>
                                                    <svg viewBox="0 0 24 24" class="w-4 h-4 fill-zinc-400 dark:fill-zinc-600">
                                                        <path fill="none" d="M0 0H24V24H0z"/>
                                                        <path d="M19 3l4 5h-3v12h-2V8h-3l4-5zm-5 15v2H3v-2h11zm0-7v2H3v-2h11zm-2-7v2H3V4h9z"/>
                                                    </svg>
                                                </svws-ui-icon>
                                                <svws-ui-icon v-show="column.sortable && !sorting.asc && column.id === sorting.column">
                                                    <span class="sr-only">Absteigend</span>
                                                    <svg viewBox="0 0 24 24" class="w-4 h-4 fill-zinc-400 dark:fill-zinc-600">
                                                        <path fill="none" d="M0 0H24V24H0z"/>
                                                        <path d="M20 4v12h3l-4 5-4-5h3V4h2zm-8 14v2H3v-2h9zm2-7v2H3v-2h11zm0-7v2H3V4h11z"/>
                                                    </svg>
                                                </svws-ui-icon>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(schueler, index) in state.schueler" :key="schueler.ext_id">
                                    <td>{{ schueler.ext_id }}</td>
                                    <td>{{ schueler.vorname }}</td>
                                    <td>{{ schueler.nachname }}</td>
                                    <td><BemerkungenIndicator @open="openFloskelMenu(index, 'asv')" :bemerkung="Boolean(schueler.asv)"></BemerkungenIndicator></td>
                                    <td><BemerkungenIndicator @open="openFloskelMenu(index, 'aue')" :bemerkung="Boolean(schueler.aue)"></BemerkungenIndicator></td>
                                    <td><BemerkungenIndicator @open="openFloskelMenu(index, 'zb')" :bemerkung="Boolean(schueler.zb)"></BemerkungenIndicator></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>

            <template #contentSidebar>
                <FloskelnMenu :selected="state.selected" :floskelgruppen="state.floskelgruppen" @close="closeFloskelMenu" @updated="updateSchueler"></FloskelnMenu>
            </template>
        </SvwsUiAppLayout>
    </div>
</template>

<style scoped>
    table > thead {
        box-shadow: 0 1px 0 0 rgba(192, 192, 192, 1);
    }

    table > tbody > tr > td {
        @apply w-0 border py-2 px-3 text-black dark:text-zinc-300 border-zinc-300 dark:border-zinc-600
    }
</style>