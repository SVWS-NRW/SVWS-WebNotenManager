<script setup lang="ts">
    import { ref } from 'vue';
    import { Inertia } from '@inertiajs/inertia'
    import { usePage } from '@inertiajs/inertia-vue3'

    import {
        SvwsUiAppLayout,
        SvwsUiSidebarMenu,
        SvwsUiSidebarMenuHeader,
        SvwsUiSidebarMenuItem,
    } from '@svws-nrw/svws-ui'

    let props = defineProps({
        title: String,
    })

    const isCollapsed = ref(false)

    let links: { label: string, route: string, icon: string }[] = [ // TODO: Icons not working
        { label: 'Notenmanager', route: 'mein_unterricht', icon: 'team' },
        { label: 'Leistungsdatenübersicht', route: 'leistungsdatenuebersicht', icon: 'book-read' },
        { label: 'Klassenleitung', route: 'klassenleitung', icon: 'user2' },
    ]

    const activePage = (routeName: string): boolean => route().current(routeName)
    const navigate = (routeName: string): void => Inertia.get(route(routeName))
    const logout = (): void => Inertia.post(route('logout'))
    const toggleCollapse = (event: Event): boolean => isCollapsed.value = !isCollapsed.value

    const username = (): string => {
        let user = usePage().props.value.auth.user

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
                        :key="link.label"
                        :icon="link.icon"
                        :active="activePage(link.route)"
                        :collapsed="isCollapsed"
                        @click="navigate(link.route)"
                    >
                        <template #label>
                            {{ link.label }}
                        </template>
                    </SvwsUiSidebarMenuItem>
                </template>
                <template #footer>
                    <SvwsUiSidebarMenuItem
                        :collapsed="isCollapsed"
                        icon="logout"
                        @click="logout()"
                    >
                        <template #label>
                            Abmelden
                        </template>
                    </SvwsUiSidebarMenuItem>
                </template>
            </SvwsUiSidebarMenu>
        </template>
        <template #main>
            <div class="component">
                <main>
                    <slot name="main" />
                </main>
            </div>
        </template>
<!--        <template #secondaryMenu>-->
<!--            <div class="component">-->
<!--                <main>-->
<!--                </main>-->
<!--            </div>-->
<!--        </template>-->
    </SvwsUiAppLayout>
</template>
