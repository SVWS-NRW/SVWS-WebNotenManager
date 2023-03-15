<script setup lang="ts">
    import { useStore } from '../../store'
    import {Inertia} from "@inertiajs/inertia";
    const navigate = (routeName: string): void => Inertia.get(route(routeName))

    import {SvwsUiSecondaryMenu, SvwsUiSidebarMenuItem} from '@svws-nrw/svws-ui'
    import AppLayout from '../../Layouts/AppLayout.vue'

    const store = useStore();

    let props = defineProps({
        auth: Object,
    })
</script>

<template>
    <div>
        <AppLayout title="Einstellungen">
            <template #main>

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
        </AppLayout>
    </div>
</template>

<style scoped>

</style>