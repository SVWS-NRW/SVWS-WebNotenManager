<script setup lang="ts">
    import { Ref, ref, onMounted } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton, SvwsUiCheckbox } from '@svws-nrw/svws-ui'
    import UserSettingsMenu from '@/Components/UserSettingsMenu.vue'

    let props = defineProps({
        auth: Object,
    })

    let user_settings = ref({
        filters_leistungsdatenuebersicht: {
            teilleistungen: false,
            fachlehrer: false,
            mahnungen: false,
            bemerkungen: false,
        },
        filters_meinunterricht: {
            teilleistungen: false,
            mahnungen: false,
            fehlstunden: false,
            bemerkungen: false,
                        kurs: false,
            note: false,
            fach:false
        }})

    onMounted((): AxiosResponse => axios.get(route('user_settings.get_all_filters'))
        .then((response: AxiosResponse): AxiosResponse => user_settings.value = response.data)
    )

    const saveSettings = () => axios
        .post(route('user_settings.set_filters'), {
            'filters_leistungsdatenuebersicht': {
                'mahnungen': user_settings.value.filters_leistungsdatenuebersicht?.mahnungen,
                'fachlehrer': user_settings.value.filters_leistungsdatenuebersicht?.fachlehrer,
                'bemerkungen': user_settings.value.filters_leistungsdatenuebersicht?.bemerkungen,
                'teilleistungen': user_settings.value.filters_leistungsdatenuebersicht?.teilleistungen,
                'kurs': user_settings.value.filters_leistungsdatenuebersicht?.kurs,
                'note': user_settings.value.filters_leistungsdatenuebersicht?.note,
                'fach': user_settings.value.filters_leistungsdatenuebersicht?.fach,
            },
            'filters_meinunterricht': {
                'mahnungen': user_settings.value.filters_meinunterricht?.mahnungen,
                'bemerkungen': user_settings.value.filters_meinunterricht?.bemerkungen,
                'fehlstunden': user_settings.value.filters_meinunterricht?.fehlstunden,
                'teilleistungen': user_settings.value.filters_meinunterricht?.teilleistungen,
                'kurs': user_settings.value.filters_meinunterricht?.kurs,
                'note': user_settings.value.filters_meinunterricht?.note,
                'fach': user_settings.value.filters_meinunterricht?.fach,
            },
        })
        .then((): void => apiSuccess())
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern von Filtern'
        ))
</script>

<template>
    <AppLayout title="Benutzereinstellungen">
        <template #main>
            <section>
                <h2 class="text-headline">Einstellungen - Filter</h2>
                <div>
                    <h3 class="text-headline-md">Mein Unterricht</h3>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.teilleistungen" :value="true">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.mahnungen">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.fehlstunden">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.bemerkungen">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.kurs">Kurs</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.note">Note</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.fach">Fach</SvwsUiCheckbox>
                </div>

                <div>
                    <h3 class="text-headline-md">Leistungsdatenübersicht</h3>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.teilleistungen">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.fachlehrer">Fachlehrer</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.mahnungen">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.bemerkungen">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.kurs">Kurs</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.note">Note</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.fach">Fach</SvwsUiCheckbox>
                </div>

                <SvwsUiButton @click="saveSettings" class="button">Speichern</SvwsUiButton>
            </section>
        </template>
        <template #secondaryMenu>
            <UserSettingsMenu></UserSettingsMenu>
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
