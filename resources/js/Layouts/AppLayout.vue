<script setup lang="ts">
    import { ref } from 'vue';
    import { Inertia } from '@inertiajs/inertia'
    import { usePage } from '@inertiajs/inertia-vue3'
    import { useSlots } from 'vue'
    import { Auth } from '../Interfaces/Auth'

    import {
        SvwsUiAppLayout,
        SvwsUiMenu,
        SvwsUiMenuHeader,
        SvwsUiMenuItem,
        SvwsUiIcon,
    } from '@svws-nrw/svws-ui'

    const modal = ref<any>(null);
    const modalInfo = ref<any>(null);
    const isCollapsed = ref(false)

    const slots = useSlots()

    const auth: Auth = usePage().props.value.auth

    const visible = (link: string): bool => {
        return {
            'mein_unterricht': !auth.administrator || (auth.user.lerngruppen.length > 0 && auth.administrator),
            'klassenleitung': auth.user.klassen.length > 0  || auth.administrator,
            'settings': auth.administrator,
        }[link]
    }

    const navigate = (routeName: string): void => Inertia.get(route(routeName))
    const logout = (): void => Inertia.post(route('logout'))

    const activePage = (routeName: string): boolean => route().current(routeName)
    const toggleCollapse = (): boolean => isCollapsed.value = !isCollapsed.value

</script>

<template>
    <SvwsUiAppLayout :fullwidthContent="true" v-cloak>
        <template #sidebar>
            <SvwsUiMenu :collapsed="isCollapsed" @toggle="toggleCollapse">
                <template #header>
                    <SvwsUiMenuHeader :collapsed="isCollapsed">
                        {{ auth.user.vorname }} {{ auth.user.nachname }}
                    </SvwsUiMenuHeader>
                </template>

                <template #default>
                    <SvwsUiMenuItem
                        :collapsed="isCollapsed"
                        :active="activePage('mein_unterricht')"
                        @click="navigate('mein_unterricht')"
                        v-if="visible('mein_unterricht')"
                    >
                        <template #icon>
                            <SvwsUiIcon>
                                <mdi-home-outline />
                            </SvwsUiIcon>
                        </template>
                        <template #label>
                            Notenmanager
                        </template>
                    </SvwsUiMenuItem>

                    <SvwsUiMenuItem
                        :collapsed="isCollapsed"
                        :active="activePage('leistungsdatenuebersicht')"
                        @click="navigate('leistungsdatenuebersicht')"
                    >
                        <template #icon>
                            <SvwsUiIcon>
                                <mdi-book-open-outline />
                            </SvwsUiIcon>
                        </template>
                        <template #label>
                            Leistungsdatenübersicht
                        </template>
                    </SvwsUiMenuItem>

                    <SvwsUiMenuItem
                        :collapsed="isCollapsed"
                        :active="activePage('klassenleitung')"
                        @click="navigate('klassenleitung')"
                        v-if="visible('klassenleitung')"
                    >
                        <template #icon>
                            <SvwsUiIcon>
                                <mdi-user-outline/>
                            </SvwsUiIcon>
                        </template>
                        <template #label>
                            Klassenleitung
                        </template>
                    </SvwsUiMenuItem>
                </template>

                <template #footer>
                    <SvwsUiMenuItem
                        :collapsed="isCollapsed"
                        @click="navigate('settings.index')"
                        v-if="visible('settings')"
                    >
                        <template #icon>
                            <SvwsUiIcon>
                                <mdi-cog-outline />
                            </SvwsUiIcon>
                        </template>
                        <template #label>
                            Einstellungen
                        </template>
                    </SvwsUiMenuItem>

                    <SvwsUiMenuItem
                        :collapsed="isCollapsed"
                        @click="logout()"
                    >
                        <template #icon>
                            <SvwsUiIcon>
                                <mdi-logout />
                            </SvwsUiIcon>
                        </template>
                        <template #label>
                            Abmelden
                        </template>
                    </SvwsUiMenuItem>
                </template>

                <template #version>
                    {{ usePage().props.value.version }}
                </template>
            </SvwsUiMenu>
        </template>

        <template #main>
            <slot name="main" />
        </template>

        <template #secondaryMenu v-if="slots.secondaryMenu">
            <slot name="secondaryMenu" />
        </template>
    </SvwsUiAppLayout>
</template>
