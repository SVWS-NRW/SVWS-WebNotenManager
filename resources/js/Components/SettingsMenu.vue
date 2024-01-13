<script setup lang="ts">
    import { Inertia } from "@inertiajs/inertia";

    import {
        SvwsUiSecondaryMenu,
        SvwsUiMenuItem,
    } from '@svws-nrw/svws-ui'

    const navigate = (routeName: string): void => Inertia.get(route(routeName))
</script>

<template>
    <SvwsUiSecondaryMenu>
        <template #headline>
            Einstellungen
        </template>
        <template #content>
            <div class="container">
                <SvwsUiMenuItem @click="navigate('settings.matrix')" :active="route().current('settings.matrix')">
                    <template #label>Schreibrechte bearbeiten</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('settings.filter')" :active="route().current('settings.filter')">
                    <template #label>Filter bearbeiten</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('settings.school')" :active="route().current('settings.school')">
                    <template #label>Schule bearbeiten</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('settings.sicherheit')" :active="route().current('settings.sicherheit')">
                    <template #label>Sicherheit & Datenaustausch</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('settings.synchronisation')" :active="route().current('settings.synchronisation')">
                    <template #label>Synchronisation</template>
                </SvwsUiMenuItem>

            </div>
        </template>
    </SvwsUiSecondaryMenu>
</template>

<style scoped>
    .container {
        @apply flex flex-col gap-3
    }
</style>