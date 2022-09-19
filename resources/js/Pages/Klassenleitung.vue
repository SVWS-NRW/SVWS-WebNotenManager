<script setup lang="ts">

    import {onMounted, reactive} from 'vue'

    import { useStore } from '../store'
    import Menubar from '../Components/Menubar.vue'
    import TopMenu from "../Components/TopMenu.vue"
    import BemerkungenIndicator from "../Components/Klassenleitung/BemerkungenIndicator.vue"
    import FloskelnMenu from "../Components/Klassenleitung/FloskelnMenu.vue"

    import axios, {AxiosPromise, AxiosResponse} from 'axios'

    type column = { key: string, label: string, sortable: boolean }
    type floskel = { gruppe: string, id: number, kuerzel: string, text: string }
    type floskelgruppe = { kuerzel: string, floskeln: floskel }
    type selected = { schueler: schueler, floskelgruppe: String }|null
    type schueler = { vorname: string, nachname: string, asv: string|null, aue: string|null, zb: string|null }

    const store = useStore()


    let props = defineProps({
        auth: Object,
    })

    let state = reactive({

        floskelgruppen: <floskelgruppe[]> [],
        schueler: <schueler[]> [],
        selected: <selected> null,
    })

    const columns = <column[]>[
        {key: 'vorname', label: 'Vorname', sortable: true},
        {key: 'nachname', label: 'Nachname', sortable: true},
        {key: 'asv', label: 'Asv', sortable: true},
        {key: 'aue', label: 'Aue', sortable: true},
        {key: 'zb', label: 'Zb', sortable: true},
    ]

    onMounted((): void => {
        fetchSchueler()
        fetchFloskelGruppen()
    })


    const fetchSchueler = (): AxiosPromise => axios.get(route('get_schueler')).then((res: AxiosResponse) => state.schueler = res.data)
    const fetchFloskelGruppen = (): AxiosPromise => axios.get(route('get_floskeln')).then((res: AxiosResponse) => state.floskelgruppen = res.data)
    const openFloskelMenu = (selected: selected): selected => state.selected = selected
    const closeFloskelMenu = (): selected|null => state.selected = null


<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
                <Menubar :auth="props.auth" />
            </template>

            <template #main>
                <div class="relative flex flex-col w-full h-screen">
                    <TopMenu headline="Klassenleitung"></TopMenu>


                    <div class="h-full flex-1 overflow-auto">
                        <SvwsUiNewTable :data="state.schueler" :columns="columns" class="relative">
                            <template #cell-asv="{ row }">
                                <BemerkungenIndicator @open="openFloskelMenu({ schueler: row, floskelgruppe: 'asv' })" :bemerkung="Boolean(row.asv)"></BemerkungenIndicator>
                            </template>
                            <template #cell-aue="{ row }">
                                <BemerkungenIndicator @open="openFloskelMenu({ schueler: row, floskelgruppe: 'aue'})" :bemerkung="Boolean(row.aue)"></BemerkungenIndicator>
                            </template>
                            <template #cell-zb="{ row }">
                                <BemerkungenIndicator @open="openFloskelMenu({ schueler: row, floskelgruppe: 'zb' })" :bemerkung="Boolean(row.zb)"></BemerkungenIndicator>
                            </template>
                        </SvwsUiNewTable>

                    </div>
                </div>
            </template>

            <template #contentSidebar>

                <FloskelnMenu :selected="state.selected" :floskelgruppen="state.floskelgruppen" @close="closeFloskelMenu" @updated="fetchSchueler"></FloskelnMenu>
            </template>
        </SvwsUiAppLayout>
    </div>
</template>
