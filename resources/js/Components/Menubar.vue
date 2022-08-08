<script setup lang="ts">
    import { useStore } from '../store.js'
    import { Inertia } from '@inertiajs/inertia';

    const store = useStore();
    const logout = () => Inertia.post(route('logout'));

    let props = defineProps({
        auth: Object,
    });

    const items =  [
        {
            caption: "Schüler",
            value: "schueler",
        },
        {
            caption: "Schule",
            value: "schule",
        },
        {
            caption: "Kataloge",
            value: "kataloge",
        },
        {
            caption: "Notenmanager",
            value: "notenmanager",
        },
    ];
</script>

<style scoped>
    .svws-ui--sidebar--menu-header {
        @apply py-3
    }
</style>

<template>
    <SvwsUiSidebarMenu :collapsed="store.sidebarCollapsed" @toggle="store.toggleSidebar">
        <template #header>
            <SvwsUiSidebarMenuHeader :collapsed="store.sidebarCollapsed">SVWS-NRW</SvwsUiSidebarMenuHeader>
        </template>

        <template #default>
            <SvwsUiSidebarMenuItem v-for="item in items" :key="item.caption" :collapsed="store.sidebarCollapsed">
                <template #label>{{ item.caption }}</template>
                <template #icon>
                     <SvwsUiIcon>
                        <i-ri-team-line aria-hidden="true" v-if="item.value === 'schueler'"></i-ri-team-line>
                        <i-ri-building-line aria-hidden="true" v-if="item.value === 'schule'"></i-ri-building-line>
                        <i-ri-apps-2-line aria-hidden="true" v-if="item.value === 'kataloge'"></i-ri-apps-2-line>
                        <i-ri-newspaper-fill aria-hidden="true" v-if="item.value === 'notenmanager'"></i-ri-newspaper-fill>
                    </SvwsUiIcon>
                </template>
            </SvwsUiSidebarMenuItem>
        </template>
        <template #footer>
            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed">
                <template #label>Einstellungen</template>
                <template #icon>
                    <SvwsUiIcon>
                        <i-ri-settings-3-line aria-hidden="true"></i-ri-settings-3-line>
                    </SvwsUiIcon>
                </template>
            </SvwsUiSidebarMenuItem>

            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" subline="Abmelden" @click="logout">
                <template #label>{{ auth.user.vorname }} {{ auth.user.nachname }}</template>
                <template #icon>
                    <SvwsUiIcon>
                        <i-ri-logout-box-line aria-hidden="true"></i-ri-logout-box-line>
                    </SvwsUiIcon>
                </template>
            </SvwsUiSidebarMenuItem>
        </template>
    </SvwsUiSidebarMenu>
</template>
