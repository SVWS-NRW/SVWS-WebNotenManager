<script setup lang="ts">
    import {Inertia} from "@inertiajs/inertia";
    import {reactive} from "vue";
    import AuthLayout from "../../Layouts/AuthLayout.vue";
    import axios, {AxiosError, AxiosResponse} from 'axios'

    defineProps({
        status: String,
        settings: Array,
    });

    let data = reactive({
        form:{
            email: '',
            password: '',
            remember: false,
        },
        processing: false,
        errors: []
    })

    const getError = (column) => data.errors[column][0]
    const hasErrors = (column) => column in data.errors


    const requestPassword = () => Inertia.get(route('request_password'))

    const submit = () => {
        data.processing = true
        axios.post(route('login'), data.form)
            .then(() => Inertia.get(route('dashboard')))
            .catch((error): AxiosError => data.errors = error.response.data.errors)
            .finally((): boolean => data.processing = false)
    }
</script>

<template>
    <div v-cloak>
        <Head>
            <title>Log in</title>
        </Head>

        <AuthLayout>
            <template #sidebar>
                <div class="space-y-6">
                    <h2 class="headline-4">{{ settings.school_name }}</h2>

                    <SvwsUiTextInput v-model="data.form.email" type="email" placeholder="E-Mail-Adresse" required :disabled="data.processing" v-on:keyup.enter="submit" />
                    <span v-if="hasErrors('email')" class="error">{{ getError('email')}}</span>

                    <SvwsUiTextInput v-model="data.form.password" type="password" placeholder="Passwort" required :disabled="data.processing" v-on:keyup.enter="submit" />
                    <span v-if="hasErrors('password')" class="error">{{ getError('password')}}</span>

                    <SvwsUiCheckbox v-model="data.form.remember" :disabled="data.processing">Angemeldet bleiben</SvwsUiCheckbox>
                    <div class="flex justify-between gap-6">
                        <SvwsUiButton @click="submit()" :disabled="data.processing">Anmelden</SvwsUiButton>
                        <SvwsUiButton @click="requestPassword()">Passwort anfordern</SvwsUiButton>
                    </div>
                </div>
            </template>
            <template #main></template>
        </AuthLayout>
    </div>
</template>

<style scoped>
    span.error {
        @apply text-red-500 text-sm mt-2
    }
</style>

