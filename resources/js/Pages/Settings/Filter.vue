<script setup lang="ts">
    import { reactive } from 'vue'
    import AppLayout from '../../Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import SettingsMenu from '../../Components/SettingsMenu.vue'
    import {SvwsUiTextInput, SvwsUiButton, SvwsUiCheckbox} from '@svws-nrw/svws-ui'

    let props = defineProps({
        settings: Object,
        auth: Object,
    })

    let settings = reactive({})

    axios.get(route('api.settings.index', 'filter'))
        .then((response: AxiosResponse): void => populateValues(response.data))

    const populateValues = (data: { key:string, value: Number }[]): void =>
        data.forEach((item): boolean => settings[item.key] = item.value == 1)

    const saveSettings = () => axios
        .put(route('api.settings.update', {type: 'filter', settings: settings}))
</script>

<template>
    <AppLayout title="Einstellungen">
        <template #main>
                <header>
                    <div id="headline">
                        <h2 class="text-headline">Einstellungen - Filter</h2>
                    </div>
                </header>
            <div class="content">
                <div>
                    <h3 class="headline-3">Mein Unterricht</h3>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_mahnungen" :value="1">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_fehlstunden" :value="1">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_bemerkungen" :value="1">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                </div>
                <div>
                    <h3 class="headline-3">Leistungsdatenübersicht</h3>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_fachlehrer" :value="1">Fachlehrer</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_mahnungen" :value="1">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_bemerkungen" :value="1">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                </div>

                <SvwsUiButton @click="saveSettings" class="button">Speichern</SvwsUiButton>
            </div>
        </template>
        <template #secondaryMenu>
            <SettingsMenu></SettingsMenu>
        </template>
    </AppLayout>
</template>

<style scoped>
    header {
        @apply ui-flex ui-flex-col ui-gap-4 ui-p-6
    }

    header #headline {
        @apply ui-flex ui-items-center ui-justify-start ui-gap-6
    }

    .content {
        @apply ui-px-6 ui-flex ui-flex-col ui-gap-12 ui-max-w-lg
    }

    .content > div {
        @apply ui-flex ui-flex-col ui-gap-5 ui-justify-start
    }

    .button {
        @apply ui-self-start
    }
</style>