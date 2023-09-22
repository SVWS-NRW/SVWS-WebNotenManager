<script setup lang="ts">
    import { Ref, ref, onBeforeMount } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton, SvwsUiCheckbox } from '@svws-nrw/svws-ui'
    import UserSettingsMenu from '@/Components/UserSettingsMenu.vue'

    let props = defineProps({
        auth: Object,
    })

    //TODO: solve errors
    //let user_settings: Ref<{}> = ref({})
    let user_settings: Ref<{
        filters_leistungsdatenuebersicht: {
            teilleistungen?: boolean,
            fachlehrer?: boolean,
            mahnungen?: boolean,
            bemerkungen?: boolean,
        }
        filters_meinunterricht: {
            teilleistungen?: boolean,
            mahnungen?: boolean,
            fehlstunden?: boolean,
            bemerkungen?: boolean,
        }

     }> = ref({})

    //TODO: if no databank exists? check
    axios.get(route('user_settings.get_all_filters'))
        .then((response: AxiosResponse) => user_settings.value = response.data)
        
    const saveSettings = () => axios
        .post(route('user_settings.set_filters'),  {
            'filters_leistungsdatenuebersicht': {
                'mahnungen': user_settings.value.filters_leistungsdatenuebersicht.mahnungen,
                'fachlehrer': user_settings.value.filters_leistungsdatenuebersicht.fachlehrer,
                'bemerkungen': user_settings.value.filters_leistungsdatenuebersicht.bemerkungen,
                'teilleistungen': user_settings.value.filters_leistungsdatenuebersicht.teilleistungen,
            },
            'filters_meinunterricht': {
                'mahnungen': user_settings.value.filters_meinunterricht.mahnungen,
                'bemerkungen': user_settings.value.filters_meinunterricht.bemerkungen,
                'fehlstunden': user_settings.value.filters_meinunterricht.fehlstunden,
                'teilleistungen': user_settings.value.filters_meinunterricht.teilleistungen,
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
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.teilleistungen">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.mahnungen">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.fehlstunden">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.bemerkungen">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                </div>

                <div>
                    <h3 class="text-headline-md">Leistungsdatenübersicht</h3>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.teilleistungen">Teilleistungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.fachlehrer">Fachlehrer</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.mahnungen">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.bemerkungen">Fachbezogene Bemerkungen</SvwsUiCheckbox>
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
