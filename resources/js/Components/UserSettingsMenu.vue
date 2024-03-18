<template>
    <SvwsUiSecondaryMenu>
        <template #headline>
            Persönliche Einstellungen
        </template>
        <template #content>
            <div class="container">
                <SvwsUiMenuItem @click="navigate('user_settings.filter')" :active="route().current('user_settings.filter')">
                    <template #label>Spalteneinstellungen</template>
                </SvwsUiMenuItem>
            </div>
        </template>
    </SvwsUiSecondaryMenu>
</template>


<script setup lang="ts">
    import { Inertia } from "@inertiajs/inertia";
    import { SvwsUiSecondaryMenu, SvwsUiMenuItem } from '@svws-nrw/svws-ui';

    const navigate = (routeName: string): void => Inertia.get(route(routeName));
</script>


<style scoped>
    .container {
        @apply flex flex-col gap-3 ml-[1.1rem]
    }
    
</style>