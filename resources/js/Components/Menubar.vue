<script setup lang="ts">
    import { useStore } from '../store.js'
    import { Inertia } from '@inertiajs/inertia'

    import { Auth } from '../Interfaces/Auth'

    const store = useStore()

    let props = defineProps<{
        auth: Auth
    }>()

    const logout = (): void => Inertia.post(route('logout'))
    const navigate = (routeName: string): void => Inertia.get(route(routeName))
    const toggleSidebar = (value: boolean): boolean => store.sidebarCollapsed = value
</script>

<template>
    <SvwsUiSidebarMenu :collapsed="store.sidebarCollapsed" @toggle="toggleSidebar">
        <template #header>
            <SvwsUiSidebarMenuHeader :collapsed="store.sidebarCollapsed">SVWS-NRW</SvwsUiSidebarMenuHeader>
        </template>

        <template #default>
            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" @click="navigate('dashboard')" :active="route().current('dashboard')">
                <template #label>Notenmanager</template>
                <template #icon>
                     <SvwsUiIcon>
                        <i-ri-team-line aria-hidden="true"></i-ri-team-line>
                    </SvwsUiIcon>
                </template>
            </SvwsUiSidebarMenuItem>
            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" @click="navigate('leistungsdatenuebersicht')" :active="route().current('leistungsdatenuebersicht')">
                <template #label>Leistungsdatenübersicht</template>
                <template #icon>
                     <SvwsUiIcon>
                        <i-ri-book-read-line aria-hidden="true"></i-ri-book-read-line>
                    </SvwsUiIcon>
                </template>
            </SvwsUiSidebarMenuItem>
            <SvwsUiSidebarMenuItem v-if=" props.auth.user.klassen.length > 0" :collapsed="store.sidebarCollapsed" @click="navigate('klassenleitung')" :active="route().current('klassenleitung')">
                <template #label>Klassenleitung</template>
                <template #icon>
                     <SvwsUiIcon>
                         <i-ri-user2-line aria-hidden="true"></i-ri-user2-line>
                    </SvwsUiIcon>
                </template>
            </SvwsUiSidebarMenuItem>
        </template>

        <template #footer>
            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" :active="route().current('settings.*')" @click="navigate('settings.index')" v-if="props.auth.administrator">
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



<style scoped>
    .sidebar--menu-header {
        @apply py-3
    }
</style>