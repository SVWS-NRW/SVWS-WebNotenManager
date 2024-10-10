<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="content">
                <div>
                    <SvwsUiCheckbox v-model="enabled" :value="true" type="toggle">Zwei-Faktor-Authentifizierung per E-Mail
                    </SvwsUiCheckbox>
                </div>
                <div style="padding-right: 28%">
                    <p>Mit der Aktivierung der Zwei-Faktor-Authentifizierung per E-Mail wird diese für alle User verpflichtend.</p>
                    <p>Nach der Aktivierung ist bei jedem Anmeldevorgang neben dem Passwort zusätzlich die Eingabe eines einmaligen Bestätigungscodes erforderlich.
                    Dieser Code wird an die im System gespeicherte E-Mail-Adresse gesendet. Die zusätzliche Sicherheitsmaßnahme dient dem Schutz Ihres Systems
                    vor unbefugtem Zugriff.</p>
                    <p>Bitte stellen Sie sicher, dass alle Benutzer Zugriff auf ihre E-Mail-Konten haben.</p>
                </div>
                <!-- <SvwsUiButton @click="saveSettings" :disabled="!isDirty">Speichern</SvwsUiButton> -->
            </div>
        </template>
        <template #secondaryMenu>
            <SettingsMenu></SettingsMenu>
        </template>
    </AppLayout>
</template>


<script setup lang="ts">
    import { Ref, ref, watch, reactive } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosResponse } from 'axios';
    import { Inertia } from '@inertiajs/inertia';
    import SettingsMenu from '@/Components/SettingsMenu.vue';
    import { apiError, apiSuccess } from '@/Helpers/api.helper';
    import { SvwsUiHeader, SvwsUiButton, SvwsUiCheckbox, SvwsUiTextInput, SvwsUiSelect } from '@svws-nrw/svws-ui';
    import { MailSendCredentialsFormData as MailSendCredentials } from '../../Interfaces/Interface';

    let props = defineProps({
        auth: Object,
    });

    const title = 'Sicherheitseinstellungen';

    //TODO: fetch 2FA data from backend
    const enabled = ref(false);

    const storedDataForm: Ref<String> = ref('');
    const isDirty: Ref<boolean> = ref(true);

        //backend doesn't exist yet
    // axios.get(route('api.xxx'))
    //     .then((response: AxiosResponse): void => {
    //         //
    //     });

    //TODO: save 2FA data too
    const saveSettings = () => 
    // axios
    //     .put(route('api.xxx'), {
    //     })
    //     .then((): void => apiSuccess())

    //TODO: check if wanted when backend is ready
    watch(() => data.form, (): void => {
        isDirty.value = JSON.stringify(data.form) !== storedDataForm.value;
    }, {
        deep: true,
    });
</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6
    }

    header #headline {
        @apply flex items-center justify-start gap-6 ml-6
    }

    /* button {
        @apply self-start
    } */

    .content {
        @apply px-11 flex flex-col gap-12
    }

    .content>div {
        @apply flex flex-col gap-5 justify-start
    }

    .error {
        @apply text-red-500 text-sm
    }
</style>