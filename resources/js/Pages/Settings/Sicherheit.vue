<script setup lang="ts">
    import { Ref, ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import SettingsMenu from '@/Components/SettingsMenu.vue'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton, SvwsUiCheckbox, SvwsUiTextInput } from '@svws-nrw/svws-ui'

    let props = defineProps({
        auth: Object,
    })

    let settings = ref({})
    //TODO: fetch 2FA data from backend
    const enabled = ref(false);

    axios.get(route('api.settings.mail_send_credentials'))
        .then((response: AxiosResponse) => settings.value = response.data)

    //TODO: save 2FA data too
    const saveSettings = () => axios
        .put(route('api.settings.mail_send_credentials'), {
            'MAIL_MAILER': settings.value.mailer,
            'MAIL_HOST': settings.value.host,
            'MAIL_PORT': settings.value.port,
            'MAIL_USERNAME': settings.value.username,
            'MAIL_PASSWORD': settings.value.password,
            'MAIL_ENCRYPTION': settings.value.encryption,
            'MAIL_FROM_ADDRESS': settings.value.from_address,
            'MAIL_FROM_NAME': settings.value.from_name,
        })
        .then((): void => apiSuccess())
        .catch((error: any): void => apiError(
            error,
            'Speichern der Änderungen fehlgeschlagen!'
        ))
</script>

<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">Einstellungen - Sicherheit</h2>
                </div>
            </header>
            <div class="content">
                <div>
                    <SvwsUiCheckbox v-model="enabled" :value="true">Zweifaktor Authentisierung anschalten
                    </SvwsUiCheckbox>
                </div>
                <div>
                    <SvwsUiTextInput v-model="settings.mailer" placeholder="Mailer"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.host" placeholder="HOST_URL"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.port" placeholder="PORT"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.username" placeholder="Benutzername"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.password" placeholder="Passwort"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.encryption" placeholder="Verschluesselung"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.from_address" placeholder="No-Reply-Adresse"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.from_name" placeholder="Absender"></SvwsUiTextInput>
                </div>
                <SvwsUiButton @click="saveSettings" type="secondary">Speichern</SvwsUiButton>
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

    .content>div {
        @apply ui-flex ui-flex-col ui-gap-5 ui-justify-start
    }

    .button {
        @apply ui-self-start
    }
</style>
