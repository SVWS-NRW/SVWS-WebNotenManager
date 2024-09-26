<template>
    <AppLayout title="Sicherheitseinstellungen">
        <template #main>
            <section>
                <h2 class="text-headline">Sicherheitseinstellungen</h2>
                <div>
                    <SvwsUiCheckbox v-model="user_settings.twofactor_otp" type="toggle" style="padding-bottom: 10px">
                        Zwei-Faktor-Authentifizierung per E-Mail
                    </SvwsUiCheckbox>
                    <div style="padding-right: 38%">
                        <p>Hiermit aktivieren Sie die Zwei-Faktor-Authentifizierung per E-Mail. 
                            Nach der Aktivierung werden Sie bei jedem Anmeldevorgang neben Ihrem Passwort zusätzlich einen einmaligen Bestätigungscode eingeben müssen. 
                            Dieser Code wird Ihnen an die von Ihnen angegebene E-Mail-Adresse gesendet. Diese zusätzliche Sicherheitsmaßnahme sorgt dafür, dass Ihr Konto besser vor unbefugtem Zugriff geschützt ist.</p>
                        <br />
                        <p>Bitte stellen Sie sicher, dass Sie auf Ihre E-Mail-Adresse jederzeit Zugriff haben, da der Bestätigungscode bei jeder Anmeldung benötigt wird.</p>
                    </div>
                </div>
                <SvwsUiButton @click="saveSettings" class="button">Speichern</SvwsUiButton>
            </section>
        </template>
        <template #secondaryMenu>
            <UserSettingsMenu :lastLogin="props.auth.lastLogin" :email="props.auth.user.email"></UserSettingsMenu>
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
    import { Ref, ref } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosResponse } from 'axios';
    import { apiError, apiSuccess } from '@/Helpers/api.helper';
    import { SvwsUiButton, SvwsUiCheckbox } from '@svws-nrw/svws-ui';
    import UserSettingsMenu from '@/Components/UserSettingsMenu.vue';
    import { Auth } from '@/Interfaces/Interface';

    const props = defineProps<{
        auth: Auth
    }>();


    const user_settings: Ref<{twofactor_otp: boolean}> = ref({
        twofactor_otp: true
    });

    axios.post(route('user_settings.get_settings'), ['twofactor_otp'])
        .then((response: AxiosResponse): AxiosResponse => user_settings.value = response.data
    );

    const saveSettings = () => axios
        .post(route('user_settings.set_settings'), {
            'twofactor_otp': user_settings.value.twofactor_otp,
        })
        .then((): void => apiSuccess())
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern von Persönlichen Filtern'
        ))
</script>


<style scoped>
    section {
        @apply p-6 space-y-12 ml-6
    }

    section > div {
        @apply flex flex-col gap-3 items-start
    }

    button {
        @apply self-start
    }
</style>
