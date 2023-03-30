<script setup lang="ts">
import { SvwsUiSecondaryMenu, SvwsUiSidebarMenuItem } from '@svws-nrw/svws-ui'
import { Inertia } from "@inertiajs/inertia";
const navigate = (routeName: string): void => Inertia.get(route(routeName))

</script>

<template>
    <SvwsUiSecondaryMenu>
        <template #headline>
            Schulverwaltung
        </template>
        <template #content>
            <div class="container">
                <SvwsUiSidebarMenuItem @click="navigate('settings.school')" :active="route().current('settings.school')">
                    <template #label>Schule bearbeiten</template>
                </SvwsUiSidebarMenuItem>
                <SvwsUiSidebarMenuItem @click="navigate('settings.filter')" :active="route().current('settings.filter')">
                    <template #label>Filter bearbeiten</template>
                </SvwsUiSidebarMenuItem>
                <SvwsUiSidebarMenuItem @click="navigate('settings.matrix')" :active="route().current('settings.matrix')">
                    <template #label>Matrix bearbeiten</template>
                </SvwsUiSidebarMenuItem>
            </div>
        </template>
    </SvwsUiSecondaryMenu>
</template>

<style scoped>
    .container {
        @apply ui-flex ui-flex-col ui-gap-3
    }
</style>