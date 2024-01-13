<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <section>
                <h2 class="text-headline">Einstellungen - Filter</h2>

                <div>
                    <h3 class="text-headline-md">Mein Unterricht</h3>
                    <SvwsUiCheckbox v-model="settings.meinunterricht.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.meinunterricht.mahnungen" :value="1">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.meinunterricht.fehlstunden" :value="1">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.meinunterricht.bemerkungen" :value="1">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.meinunterricht.kurs" :value="1">Kurs</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.meinunterricht.note" :value="1">Note</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.meinunterricht.fach" :value="1">Fach</SvwsUiCheckbox>
                </div>

                <div>
                    <h3 class="text-headline-md">Leistungsdatenübersicht</h3>
                    <SvwsUiCheckbox v-model="settings.leistungsdatenuebersicht.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungsdatenuebersicht.fachlehrer" :value="1">Fachlehrer</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungsdatenuebersicht.mahnungen" :value="1">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungsdatenuebersicht.fehlstunden" :value="1">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungsdatenuebersicht.bemerkungen" :value="1">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungsdatenuebersicht.kurs" :value="1">Kurs</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungsdatenuebersicht.note" :value="1">Note</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="settings.leistungsdatenuebersicht.fach" :value="1">Fach</SvwsUiCheckbox>
                </div>

                <SvwsUiButton @click="saveSettings" class="button">Speichern</SvwsUiButton>
            </section>
        </template>
        <template #secondaryMenu>
            <SettingsMenu></SettingsMenu>
        </template>
    </AppLayout>
</template>


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

    //correct this

    let settings: Ref<{
        meinunterricht?: {
            teilleistungen?: boolean,
            mahnungen?: boolean,
            fehlstunden?: boolean,
            bemerkungen?: boolean,
            kurs?: boolean,
            note?: boolean,
            fach?: boolean,
        },
        leistungsdatenuebersicht?: {
            teilleistungen?: boolean,
            fachlehrer?: boolean,
            mahnungen?: boolean,
            fehlstunden?: boolean,
            bemerkungen?: boolean,
            kurs?: boolean,
            note?: boolean,
            fach?: boolean,
        }
    }> = ref({})

    axios.get(route('api.settings.filters'))
        .then((response: AxiosResponse) => settings.value = response.data)

    const saveSettings = () => axios
        .post(route('api.settings.filters'),  {
            'filters_meinunterricht': {
                'mahnungen': settings.value.meinunterricht.mahnungen,
                'bemerkungen': settings.value.meinunterricht.bemerkungen,
                'fehlstunden': settings.value.meinunterricht.fehlstunden,
                'teilleistungen': settings.value.meinunterricht.teilleistungen,
                'kurs': settings.value.meinunterricht.kurs,
                'note': settings.value.meinunterricht.note,
                'fach': settings.value.meinunterricht.fach,
            },
            'filters_leistungsdatenuebersicht': {
                'mahnungen': settings.value.leistungsdatenuebersicht.mahnungen,
                'fachlehrer': settings.value.leistungsdatenuebersicht.fachlehrer,
                'bemerkungen': settings.value.leistungsdatenuebersicht.bemerkungen,
                'fehlstunden': settings.value.leistungsdatenuebersicht.fehlstunden,
                'teilleistungen': settings.value.leistungsdatenuebersicht.teilleistungen,
                'kurs': settings.value.leistungsdatenuebersicht.kurs,
                'note': settings.value.leistungsdatenuebersicht.note,
                'fach': settings.value.leistungsdatenuebersicht.fach,
            },
        })
        .then((): void => apiSuccess())
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern von Filtern'
        ))
</script>


<style scoped>
    section {
        @apply p-6 space-y-12
    }

    section > div {
        @apply flex flex-col gap-3 items-start
    }

    button {
        @apply self-start
    }
</style>