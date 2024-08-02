<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="form-control-area">
                <div>
                    <SvwsUiCheckbox v-model="enabled" :value="true" type="toggle">Zweifaktor Authentisierung
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
    import { SvwsUiHeader, SvwsUiButton, SvwsUiCheckbox, SvwsUiTextInput, SvwsUiSelect } from '@svws-nrw/svws-ui';
    import { MailSendCredentialsFormData as MailSendCredentials } from '../../Interfaces/Interface';

    let props = defineProps({
        auth: Object,
    });

    const title = 'Zweifaktor Authentisierung';

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
        @apply flex flex-col gap-10 max-w-[32rem] px-11 pt-6
    }

    .form-control {
        @apply -my-2
    }

    .error {
        @apply text-red-500 text-sm
    }
</style>