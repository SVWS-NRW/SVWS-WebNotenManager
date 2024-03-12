<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">Mailkonfiguration</h2>
                </div>
            </header>
            <div class="form-control-area">
                <div class="form-control">
                    <SvwsUiTextInput v-model="data.form.mailer" :valid="() => !hasErrors('MAIL_MAILER')" type="text"
                        placeholder="Mailer"></SvwsUiTextInput>
                    <span v-if="hasErrors('MAIL_MAILER')" class="error">
                        {{ getError('MAIL_MAILER') }}
                    </span>
                </div>
                <div class="form-control">
                    <SvwsUiTextInput v-model="data.form.host" :valid="() => !hasErrors('MAIL_HOST')" type="text"
                        placeholder="HOST_URL"></SvwsUiTextInput>
                    <span v-if="hasErrors('MAIL_HOST')" class="error">
                        {{ getError('MAIL_HOST') }}
                    </span>
                </div>
                <div class="form-control">
                    <SvwsUiTextInput v-model="data.form.port" :valid="() => !hasErrors('MAIL_PORT')" type="text"
                        placeholder="PORT"></SvwsUiTextInput>
                    <span v-if="hasErrors('MAIL_PORT')" class="error">
                        {{ getError('MAIL_PORT') }}
                    </span>
                </div>
                <div class="form-control">
                    <SvwsUiTextInput v-model="data.form.username" :valid="() => !hasErrors('MAIL_USERNAME')" type="text"
                        placeholder="Benutzername"></SvwsUiTextInput>
                    <span v-if="hasErrors('MAIL_USERNAME')" class="error">
                        {{ getError('MAIL_USERNAME') }}
                    </span>
                </div>
                <div class="form-control">
                    <SvwsUiTextInput v-model="data.form.password" :valid="() => !hasErrors('MAIL_PASSWORD')" type="password"
                        placeholder="Passwort"></SvwsUiTextInput>
                    <span v-if="hasErrors('MAIL_PASSWORD')" class="error">
                        {{ getError('MAIL_PASSWORD') }}
                    </span>
                </div>
                <div class="form-control">
                    <SvwsUiSelect v-model="data.form.encryption" :items="verschluesselungOptions" :item-text="item => item" label="Verschlüsselung"></SvwsUiSelect>
                </div>
                <div class="form-control">
                    <SvwsUiTextInput v-model="data.form.from_address" :valid="() => !hasErrors('MAIL_FROM_ADDRESS')"
                        type="email" placeholder="No-Reply-Adresse"></SvwsUiTextInput>
                    <span v-if="hasErrors('MAIL_FROM_ADDRESS')" class="error">
                        {{ getError('MAIL_FROM_ADDRESS') }}
                    </span>
                </div>

                <div class="form-control">
                    <SvwsUiTextInput v-model="data.form.from_name" :valid="() => !hasErrors('MAIL_FROM_NAME')" type="text"
                        placeholder="Absender"></SvwsUiTextInput>
                    <span v-if="hasErrors('MAIL_FROM_NAME')" class="error">
                        {{ getError('MAIL_FROM_NAME') }}
                    </span>
                </div>
                <SvwsUiButton @click="sendTestmail" >Testmail senden</SvwsUiButton>

                <div>
                    <SvwsUiCheckbox v-model="enabled" :value="true">Zweifaktor Authentisierung
                    </SvwsUiCheckbox>
                </div>

                <SvwsUiButton @click="saveSettings" :disabled="!isDirty">Speichern</SvwsUiButton>
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
    import { SvwsUiButton, SvwsUiCheckbox, SvwsUiTextInput, SvwsUiSelect } from '@svws-nrw/svws-ui';
    import { MailSendCredentialsFormData as MailSendCredentials } from '../../Interfaces/FormData';

    let props = defineProps({
        auth: Object,
    });

    //TODO: fetch 2FA data from backend
    const enabled = ref(false);

    let data: MailSendCredentials = reactive({
        form: {
            mailer: 0,
            host: '',
            port: '',
            username: '',
            password: '',
            encryption: '',
            from_address: '',
            from_name: '',
        },
        //TODO: unused
        processing: false,
        errors: {},
        //TODO: unused
        successMessage: false,
    });

    const verschluesselungOptions: string[] = ["", "tls", "ssl"];

    const getError = (column: string): string => data.errors[column][0];
    const hasErrors = (column: string): boolean => column in data.errors;

    const storedDataForm: Ref<String> = ref('');
    const isDirty: Ref<boolean> = ref(true);

    axios.get(route('api.settings.mail_send_credentials'))
        .then((response: AxiosResponse): void => {
            data.form = response.data;
            storedDataForm.value = JSON.stringify(data.form);
        });

    //TODO: save 2FA data too
    const saveSettings = () => axios
        .put(route('api.settings.mail_send_credentials'), {
            'MAIL_MAILER': data.form.mailer,
            'MAIL_HOST': data.form.host,
            'MAIL_PORT': data.form.port,
            'MAIL_USERNAME': data.form.username,
            'MAIL_PASSWORD': data.form.password,
            'MAIL_ENCRYPTION': data.form.encryption,
            'MAIL_FROM_ADDRESS': data.form.from_address,
            'MAIL_FROM_NAME': data.form.from_name,
        })
        .then((): void => apiSuccess())
        .then((): void  => { 
                isDirty.value = false;
                storedDataForm.value = JSON.stringify(data.form);
        })
        .catch((error: any): void => {
            apiError(error, 'Speichern der Änderungen fehlgeschlagen!');
            data.errors = error.response.data.errors;
        });

    const sendTestmail = (): void => Inertia.get(route('send_testmail'));

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

    .content {
        @apply px-6 flex flex-col gap-12 max-w-lg ml-6
    }

    .content>div {
        @apply flex flex-col gap-5 justify-start
    }

    section {
        @apply p-6 space-y-12
    }

    section>div {
        @apply flex flex-col gap-3 items-start
    }

    button {
        @apply self-start
    }

    .form-control-area {
        @apply flex flex-col gap-10 max-w-[32rem] px-6 pt-8
    }

    .form-control {
        @apply -my-2
    }

    .error {
        @apply text-red-500 text-sm
    }
</style>
