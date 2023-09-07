<script setup lang="ts">
    import { Ref, ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton, SvwsUiCheckbox } from '@svws-nrw/svws-ui'
    import SettingsMenu from '@/Components/SettingsMenu.vue'

    let props = defineProps({
        auth: Object,
    })

    //TODO: is false ok? on the other hand: check type, interface, usw.
    let settings: Ref<boolean> = ref(false)

    //TODO: this is a dummy so far
    axios.get(route('api.settings.index', 'sicherheit'))
        .then((response: AxiosResponse) => settings.value = response.data)

//sthg like this with two-factor.enable)  
        //axios.post(route('two-factor.enable'), data.form)
        // .then((): void => Inertia.get(route('xxx')))
        // .catch((error: any): AxiosError => data.errors = error.response.data.errors)
        // .finally((): boolean => data.processing = false)

    const saveSettings = () => axios
        .put(route('api.settings.bulk_update', {group: 'sicherheit'}),  {settings: settings.value})
        .then((): void => apiSuccess())
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern von "Die Klassenleitung darf alle Leistungsdaten bearbeiten."'
        ))
</script>

<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <section>
                <h2 class="text-headline">Einstellungen - Sicherheit</h2>

                <div>
                    <h3 class="text-headline-md">Mein Unterricht</h3>
                    <SvwsUiCheckbox v-model="settings.xxx" :value="true">Zweifaktor Authentisierung anschalten</SvwsUiCheckbox>
                </div>

                <SvwsUiButton @click="saveSettings" class="button">Speichern</SvwsUiButton>
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

    section > div {
        @apply ui-flex ui-flex-col ui-gap-3 ui-items-start
    }

    button {
        @apply ui-self-start
    }
</style>
