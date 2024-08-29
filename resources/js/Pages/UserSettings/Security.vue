<template>
    <AppLayout title="Sicherheitseinstellungen">
        <template #main>
            <section>
                <h2 class="text-headline">Sicherheitseinstellungen</h2>
                <div>
                <h3 class="text-headline-md">2FA</h3>
                    <SvwsUiCheckbox v-model="user_settings.twofactor_otp" type="toggle">
                        OTP
                    </SvwsUiCheckbox>
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
