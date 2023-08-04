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

    let settings: Ref<{
        mein_unterricht_teilleistungen?: boolean,
        mein_unterricht_mahnungen?: boolean,
        mein_unterricht_fehlstunden?: boolean,
        mein_unterricht_bemerkungen?: boolean,
        leistungdatenuebersicht_teilleistungen?: boolean,
        leistungdatenuebersicht_fachlehrer?: boolean,
        leistungdatenuebersicht_mahnungen?: boolean,
        leistungdatenuebersicht_bemerkungen?: boolean,
    }> = ref({})

    axios.get(route('api.settings.index', 'filter'))
        .then((response: AxiosResponse) => settings.value = response.data)

    const saveSettings = () => axios
        .put(route('api.settings.bulk_update', {group: 'filter'}),  {settings: settings.value})
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
                <h2 class="text-headline">Einstellungen - Filter</h2>

                <div>
                    <h3 class="text-headline-md">Mein Unterricht</h3>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_mahnungen" :value="1">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_fehlstunden" :value="1">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_bemerkungen" :value="1">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                </div>

                <div>
                    <h3 class="text-headline-md">Leistungsdatenübersicht</h3>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_fachlehrer" :value="1">Fachlehrer</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_mahnungen" :value="1">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_bemerkungen" :value="1">Fachbezogene Bemerkungen</SvwsUiCheckbox>
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
