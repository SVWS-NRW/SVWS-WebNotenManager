<script setup lang="ts">
    import { useStore } from '../store.js'
    import { Inertia } from '@inertiajs/inertia'

    const store = useStore()

    let props = defineProps({
        auth: Object,
    })

    const logout = (): void => Inertia.post(route('logout'))
    const navigate = (routeName: string): void => Inertia.get(route(routeName))
    const toggleSidebar = (value: boolean) => store.sidebarCollapsed = value


    const darkMode = (): void => {
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.remove('dark', 'theme-dark')
            localStorage.removeItem('theme')
            store.darkmode = false
            return
        }

        document.documentElement.classList.add('dark', 'theme-dark')
        localStorage.theme = 'dark'
        store.darkmode = true
    }

    const items =  [
        {
            caption: 'Notenmanager',
            value: 'notenmanager',
            route: 'dashboard',
        },
        {
            caption: 'Klassenleitung',
            value: 'klassenleitung',
            route: 'klassenleitung',
        },
    ];
</script>

<template>
    <SvwsUiSidebarMenu :collapsed="store.sidebarCollapsed" @toggle="toggleSidebar">
        <template #header>
            <SvwsUiSidebarMenuHeader :collapsed="store.sidebarCollapsed">SVWS-NRW</SvwsUiSidebarMenuHeader>
        </template>

        <template #default>
            <SvwsUiSidebarMenuItem v-for="item in items" :key="item.caption" :collapsed="store.sidebarCollapsed" @click="navigate(item.route)" :active="route().current(item.route)">
                <template #label>{{ item.caption }}</template>
                <template #icon>
                     <SvwsUiIcon>
                        <i-ri-newspaper-fill aria-hidden="true" v-if="item.value === 'notenmanager'"></i-ri-newspaper-fill>
                        <i-ri-team-line aria-hidden="true" v-if="item.value === 'klassenleitung'"></i-ri-team-line>
                    </SvwsUiIcon>
                </template>
            </SvwsUiSidebarMenuItem>
        </template>

        <template #footer>
            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" @click.capture="darkMode">
                <template #label>Dunkelmodus</template>
                <template #icon>
                    <SvwsUiIcon>
                        <i-ri-sun-line aria-hidden="true" v-if="store.darkmode"></i-ri-sun-line>
                        <i-ri-moon-line aria-hidden="true" v-else></i-ri-moon-line>
                    </SvwsUiIcon>
                </template>
            </SvwsUiSidebarMenuItem>

            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" :active="route().current('settings')" @click="navigate('settings')">
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
    .svws-ui--sidebar--menu-header {
        @apply py-3
    }
</style>