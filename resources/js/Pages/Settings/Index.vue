<script setup lang="ts">
    import { useStore } from '../../store'
    // import Menubar from '../../Components/Menubar.vue'
    // import TopMenu from "../../Components/TopMenu.vue"
    import {Inertia} from "@inertiajs/inertia";
    const navigate = (routeName: string): void => Inertia.get(route(routeName))

    const store = useStore();

    let props = defineProps({
        auth: Object,
    })
</script>

<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
<!--                <Menubar :auth="props.auth" />-->
            </template>
            <template #secondaryMenu>
                <SvwsUiSecondaryMenu>
                    <template #headline>
                        Schulverwaltung
                    </template>

                    <template #content>
                        <SvwsUiSidebarMenuItem @click="navigate('settings.school')" :active="route().current('settings.school')">
                            <template #label>Schule bearbeiten</template>
                        </SvwsUiSidebarMenuItem>
                    </template>
                </SvwsUiSecondaryMenu>
            </template>

            <template #main>
                <div class="ui-relative ui-flex ui-flex-col ui-w-full ui-h-screen">
<!--                    <TopMenu headline="Einstellungen"></TopMenu>-->
                </div>
            </template>

            <template #contentSidebar>
            </template>
        </SvwsUiAppLayout>
    </div>
</template>

<style scoped>

</style>