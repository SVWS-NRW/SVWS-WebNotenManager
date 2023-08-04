<script setup lang="ts">
    import { ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, {AxiosResponse} from 'axios'
    import SettingsMenu from '@/Components/SettingsMenu.vue'
    import { apiError, apiSuccess } from '@/Helpers/api.helper';
    import { SvwsUiTextInput, SvwsUiButton } from '@svws-nrw/svws-ui'

    let props = defineProps({
        auth: Object,
    })

    let settings = ref({})

    axios.get(route('api.settings.index', 'general'))
        .then((response: AxiosResponse) => settings.value = response.data)

    const saveSettings = () => axios
        .put(route('api.settings.bulk_update', {group: 'general'}),  { settings: settings.value })
        .then((): void => apiSuccess())
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern von "Die Klassenleitung darf alle Leistungsdaten bearbeiten."'
        ))
</script>

<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">Einstellungen - Schule</h2>
                </div>
            </header>
            <div class="content">
                <div>
                    <h3 class="headline-3">Schule</h3>
                    <SvwsUiTextInput v-model="settings.name" placeholder="Name der Schule"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.address" placeholder="Adresse der Schule"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.email" placeholder="E-Mail Adresse der Schule"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Schulleitung</h3>
                    <SvwsUiTextInput v-model="settings.management_name" placeholder="Name Schulleitung"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.management_telephone" placeholder="Sekretariat"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.management_email" placeholder="E-Mail-Adresse"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Schulträger</h3>
                    <SvwsUiTextInput v-model="settings.board_name" placeholder="Name des Schulträgers"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.board_address" placeholder="Anschrift des Schulträgers"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.board_contact" placeholder="Kontaktdaten des Schulträgers"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Datenschutz</h3>
                    <SvwsUiTextInput v-model="settings.gdpr_email" placeholder="[Email des Datenschutzbeauftragten]"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.gdpr_address" placeholder="[Anschrift des Datenschutzbeauftragten]"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.hosting_provider_name" placeholder="[Name des Hosters]"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.hosting_provider_address" placeholder="[Anschrift des Hosters]"></SvwsUiTextInput>
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
