<script setup lang="ts">
import {computed, onMounted, reactive, watch} from 'vue'
    import { useStore } from '../store'
    import Menubar from '../Components/Menubar.vue'
    import TopMenu from "../Components/TopMenu.vue"
    import BemerkungenIndicator from "../Components/Klassenleitung/BemerkungenIndicator.vue"
    import FloskelnMenu from "../Components/Klassenleitung/FloskelnMenu.vue"
    import axios from 'axios'


    const store = useStore();

    let props = defineProps({
        auth: Object,
    })
</script>

<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
                <Menubar :auth="props.auth" />
            </template>
            <template #secondaryMenu>
                <SvwsUiSecondaryMenu>
                    <template #headline>
                        Schulverwaltung
                    </template>

                    <template #content>
                        <SvwsUiSidebarMenuItem @click="navigate('klassenleitung')" :active="route().current('klassenleitung')" >
                            <template #label>Schule bearbeiten</template>
                        </SvwsUiSidebarMenuItem>
                        <SvwsUiSidebarMenuItem @click="navigate('klassenleitung')" :active="route().current('settings')" >
                            <template #label>Einstellungen</template>
                        </SvwsUiSidebarMenuItem>
                        <SvwsUiSidebarMenuItem @click="navigate('klassenleitung')" :active="route().current('klassenleitung')" >
                            <template #label>Datenaustausch</template>
                        </SvwsUiSidebarMenuItem>
                        <SvwsUiSidebarMenuItem @click="navigate('klassenleitung')" :active="route().current('klassenleitung')" >
                            <template #label>Datensicherung</template>
                        </SvwsUiSidebarMenuItem>
                        <SvwsUiSidebarMenuItem @click="navigate('klassenleitung')" :active="route().current('klassenleitung')" >
                            <template #label>Schuljahrwechsel</template>
                        </SvwsUiSidebarMenuItem>
                        <br>
                        <SvwsUiSidebarMenuItem @click="navigate('klassenleitung')" :active="route().current('klassenleitung')" >
                            <template #label>Statistik IT.NRW</template>
                        </SvwsUiSidebarMenuItem>
                        <SvwsUiSidebarMenuItem @click="navigate('klassenleitung')" :active="route().current('klassenleitung')" >
                            <template #label>Werkzeuge</template>
                        </SvwsUiSidebarMenuItem>
                        <SvwsUiSidebarMenuItem @click="navigate('klassenleitung')" :active="route().current('klassenleitung')" >
                            <template #label>Benutzerverwaltung</template>
                        </SvwsUiSidebarMenuItem>
                        <SvwsUiSidebarMenuItem @click="navigate('klassenleitung')" :active="route().current('klassenleitung')" >
                            <template #label>Hilfe</template>
                        </SvwsUiSidebarMenuItem>
                    </template>
                </SvwsUiSecondaryMenu>
            </template>

            <template #main>
                <div class="relative flex flex-col w-full h-screen">
                    <TopMenu headline="Einstellungen"></TopMenu>
                </div>
            </template>

            <template #contentSidebar>
            </template>
        </SvwsUiAppLayout>
    </div>
</template>

<style scoped>

</style>