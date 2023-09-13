<script setup lang="ts">
    import { Ref, ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import SettingsMenu from '@/Components/SettingsMenu.vue'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton, SvwsUiCheckbox } from '@svws-nrw/svws-ui'

    //TODO: check this
    let props = defineProps({
        auth: Object,
    })


    const enabled = ref(true);

    //only going in one direction (activate/deactivate) for the moment
        //TODO: check types
    const submit = (): void => {
        if (enabled.value) {
        axios.post(route('activate2FA'))
        //.then((): void => apiSuccess())
        .then((): void => alert())
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten.'
        ))
        // .finally(() => alert(enabled.value))
        }
    };





</script>

<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <section>
                <h2 class="text-headline">Einstellungen - Sicherheit</h2>
                <h3 class="text-headline-md">Mein Unterricht</h3>
                <SvwsUiCheckbox v-model="enabled" :value="true">Zweifaktor Authentisierung anschalten</SvwsUiCheckbox>
                <SvwsUiButton @click="submit" type="secondary">Speichern</SvwsUiButton>
            </section>
        </template>
        <template #secondaryMenu>
            <SettingsMenu></SettingsMenu>
        </template>
    </AppLayout>
</template>

<style scoped>
    section {
        @apply ui-p-6 ui-space-y-12
    }

    section>div {
        @apply ui-flex ui-flex-col ui-gap-3 ui-items-start
    }

    button {
        @apply ui-self-start
    }
</style>
