<script setup lang="ts">
    import { ref } from 'vue';
    import { Inertia } from '@inertiajs/inertia'
    import { usePage } from '@inertiajs/inertia-vue3'
    import { Auth } from '../Interfaces/Auth'
    import { useSlots } from 'vue'


    import {
        SvwsUiAppLayout,
        SvwsUiSidebarMenu,
        SvwsUiSidebarMenuHeader,
        SvwsUiSidebarMenuItem,
        SvwsUiIcon,
    } from '@svws-nrw/svws-ui'

    let props = defineProps<{
        title: String,
    }>()

    const slots = useSlots()

    const isCollapsed = ref(false)

    let links: { label: string, route: string, icon: string, isVisible: boolean }[] = [
        {
            label: 'Notenmanager',
            route: 'mein_unterricht',
            icon: 'home',
            isVisible: !usePage().props.value.auth.administrator || (usePage().props.value.auth.user.lerngruppen.length > 0  && usePage().props.value.auth.administrator)
        },
        { label: 'Leistungsdatenübersicht', route: 'leistungsdatenuebersicht', icon: 'book-open', isVisible: true },
        {
            label: 'Klassenleitung',
            route: 'klassenleitung',
            icon: 'user',
            isVisible: usePage().props.value.auth.user.klassen.length > 0  || usePage().props.value.auth.administrator
        },
    ]

    const activePage = (routeName: string): boolean => route().current(routeName)
    const navigate = (routeName: string): void => Inertia.get(route(routeName))
    const logout = (): void => Inertia.post(route('logout'))
    const toggleCollapse = (): boolean => isCollapsed.value = !isCollapsed.value

    const username = (): string => {
        let user: { vorname: string, nachname: string } = usePage().props.value.auth.user

        return `${user.vorname} ${user.nachname}`
    }
</script>

<template>
    <SvwsUiAppLayout :fullwidthContent="true" v-cloak>
        <template #sidebar>
            <SvwsUiSidebarMenu :collapsed="isCollapsed">
                <template #header>
                    <SvwsUiSidebarMenuHeader :collapsed="isCollapsed" @toggle="toggleCollapse">
                        {{ username() }}
                    </SvwsUiSidebarMenuHeader>
                </template>
                <template #default>
                    <SvwsUiSidebarMenuItem
                        v-for="link in links"
                        v-show="link.isVisible"
                        :key="link.label"
                        :icon="link.icon"
                        :active="activePage(link.route)"
                        :collapsed="isCollapsed"
                        @click="navigate(link.route)"
                    >
                        <template #label>
                            {{ link.label }}
                        </template>
                        <template #icon>
                            <SvwsUiIcon>
                                <mdi-home-outline v-if="link.icon === 'home'"></mdi-home-outline>
                                <mdi-book-open-outline v-if="link.icon === 'book-open'"></mdi-book-open-outline>
                                <mdi-user-outline v-if="link.icon === 'user'"></mdi-user-outline>
                            </SvwsUiIcon>
                        </template>
                    </SvwsUiSidebarMenuItem>
                </template>
                <template #footer>
                    <SvwsUiSidebarMenuItem
                        :collapsed="isCollapsed"
                        icon="cog-outline"
                        @click="navigate('settings.index')"
                        v-if="usePage().props.value.auth.administrator"
                    >
                        <template #label>
                            Einstellungen
                        </template>
                        <template #icon>
                            <mdi-cog-outline></mdi-cog-outline>
                        </template>
                    </SvwsUiSidebarMenuItem>
                    <SvwsUiSidebarMenuItem
                        :collapsed="isCollapsed"
                        icon="logout"
                        @click="logout()"
                    >
                        <template #label>
                            Abmelden
                        </template>
                        <template #icon>
                            <mdi-logout></mdi-logout>
                        </template>
                    </SvwsUiSidebarMenuItem>
                </template>
                <template #version>
                    {{ usePage().props.value.version }}
                </template>
                <template #metaNavigation>
                    <a :href="route('impressum')" target="_blank">Impressum</a>
                    <a :href="route('datenschutz')" target="_blank">Datenschutz</a>
                </template>
            </SvwsUiSidebarMenu>
        </template>
        <template #main>
            <slot name="main" />
        </template>
        <template #secondaryMenu v-if="slots.secondaryMenu">
            <slot name="secondaryMenu" />
        </template>
    </SvwsUiAppLayout>
</template>
