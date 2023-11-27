<script setup lang="ts">

    import { Ref, ref, watch } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import SettingsMenu from '@/Components/SettingsMenu.vue'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton, SvwsUiCheckbox, SvwsUiTextInput } from '@svws-nrw/svws-ui'

    let props = defineProps({
        auth: Object,
    })
    
    //TODO: fetch 2FA data from backend
    const enabled = ref(false)

    interface Settings {
        mailer: number
        host: string
        port: string
        username: string
        password: string
        encryption: string
        from_address: string
        from_name: string
    }

    const settings: Ref<Settings> = ref({} as Settings)
    const storedSettings: Ref<String> = ref('')
    const isDirty: Ref<boolean> = ref(false)

    axios.get(route('api.settings.mail_send_credentials'))
        .then((response: AxiosResponse): void => {
            settings.value = response.data
            storedSettings.value = JSON.stringify(settings.value)
        })


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
        .then((): boolean => isDirty.value = false)
        .catch((error: any): void => apiError(
            error,
            'Speichern der Änderungen fehlgeschlagen!'
        ))

    watch(() => settings.value, (): void => {
        console.log("changed")
        if (JSON.stringify(settings.value) == storedSettings.value) {
            isDirty.value = false
        }
    }, {
        deep: true,
    })

    const updateIsDirty = (): boolean => isDirty.value = true




    //only going in one direction (activate/deactivate) for the moment
    //     //TODO: check types
    // const submit = (): void => {
    //     if (enabled.value) {
    //     axios.post(route('activate2FA'))
    //     .then((): void => apiSuccess())
    //     .catch((error: any): void => apiError(
    //         error,
    //         'Ein Problem ist aufgetreten.'
    //     ))
    //     // .finally(() => alert(enabled.value))
    //     }
    // };





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
                    <!-- TODO: ADD  @input="updateIsDirty()" and whatever needed up there when backend works -->
                    <SvwsUiCheckbox v-model="enabled" :value="true">Zweifaktor Authentisierung anschalten
                    </SvwsUiCheckbox>
                </div>
                <div>
                    <SvwsUiTextInput v-model="settings.mailer" @input="updateIsDirty()" placeholder="Mailer"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.host" @input="updateIsDirty()" placeholder="HOST_URL"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.port" @input="updateIsDirty()" placeholder="PORT"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.username" @input="updateIsDirty()" placeholder="Benutzername"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.password" @input="updateIsDirty()" placeholder="Passwort"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.encryption" @input="updateIsDirty()" placeholder="Verschluesselung"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.from_address" @input="updateIsDirty()" placeholder="No-Reply-Adresse"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.from_name" @input="updateIsDirty()" placeholder="Absender"></SvwsUiTextInput>
                </div>
                <SvwsUiButton @click="saveSettings" :disabled="!isDirty">Speichern</SvwsUiButton>
            </div>

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
