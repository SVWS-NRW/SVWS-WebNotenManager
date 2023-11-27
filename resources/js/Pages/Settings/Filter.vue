<script setup lang="ts">
    import { Ref, ref, watch } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton, SvwsUiCheckbox } from '@svws-nrw/svws-ui'
    import SettingsMenu from '@/Components/SettingsMenu.vue'

    let props = defineProps({
        auth: Object,
    })


    //TODO: adjust if personal settings comes into force and data changes
    interface Settings {
        mein_unterricht_teilleistungen: boolean,
        mein_unterricht_mahnungen: boolean,
        mein_unterricht_fehlstunden: boolean,
        mein_unterricht_bemerkungen: boolean,
        leistungdatenuebersicht_teilleistungen: boolean,
        leistungdatenuebersicht_fachlehrer: boolean,
        leistungdatenuebersicht_mahnungen: boolean,
        leistungdatenuebersicht_bemerkungen: boolean,
    }

    const settings: Ref<Settings> = ref({} as Settings)
    const storedSettings: Ref<String> = ref('')
    const isDirty: Ref<boolean> = ref(false)


    axios.get(route('api.settings.index', 'filter'))
        .then((response: AxiosResponse): void => {
            settings.value = response.data
            storedSettings.value = JSON.stringify(settings.value)
        })

    const saveSettings = () => axios
        .post(route('api.settings.filters'),  {
            'filters_leistungsdatenuebersicht': {
                'mahnungen': settings.value.leistungsdatenuebersicht.mahnungen,
                'fachlehrer': settings.value.leistungsdatenuebersicht.fachlehrer,
                'bemerkungen': settings.value.leistungsdatenuebersicht.bemerkungen,
                'teilleistungen': settings.value.leistungsdatenuebersicht.teilleistungen,
            },
            'filters_meinunterricht': {
                'mahnungen': settings.value.meinunterricht.mahnungen,
                'bemerkungen': settings.value.meinunterricht.bemerkungen,
                'fehlstunden': settings.value.meinunterricht.fehlstunden,
                'teilleistungen': settings.value.meinunterricht.teilleistungen,
            },
        })
        .then((): void => apiSuccess())
        .then((): boolean => isDirty.value = false)
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern von Filtern'
        ))

    
    watch(() => settings.value, (): void => {
        if (JSON.stringify(settings.value) == storedSettings.value) {
            isDirty.value = false
        }
    }, {
        deep: true,
    })

    const updateIsDirty = (): boolean => isDirty.value = true
</script>

<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <section>
                <h2 class="text-headline">Einstellungen - Filter</h2>

                <div>
                    <h3 class="text-headline-md">Mein Unterricht</h3>

                    <SvwsUiCheckbox v-model="settings.mein_unterricht_teilleistungen" @input="updateIsDirty()" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_mahnungen" @input="updateIsDirty()" :value="1">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_fehlstunden" @input="updateIsDirty()" :value="1">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.mein_unterricht_bemerkungen" @input="updateIsDirty()" :value="1">Fachbezogene Bemerkungen</SvwsUiCheckbox>

                </div>

                <div>
                    <h3 class="text-headline-md">Leistungsdatenübersicht</h3>

                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_teilleistungen" @input="updateIsDirty()" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_fachlehrer" @input="updateIsDirty()" :value="1">Fachlehrer</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_mahnungen" @input="updateIsDirty()" :value="1">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungdatenuebersicht_bemerkungen" @input="updateIsDirty()" :value="1">Fachbezogene Bemerkungen</SvwsUiCheckbox>

                </div>

                <SvwsUiButton @click="saveSettings" class="button" :disabled="!isDirty">Speichern</SvwsUiButton>
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
