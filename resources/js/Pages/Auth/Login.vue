<script setup lang="ts">
    import AuthLayout from '../../Layouts/AuthLayout.vue'
    import axios, { AxiosError, AxiosResponse } from 'axios'
    import { Inertia } from '@inertiajs/inertia'
    import { PropType, reactive } from 'vue'
    import { Settings } from '../../Interfaces/Settings'
    import { SvwsUiTextInput, SvwsUiCheckbox, SvwsUiButton } from '@svws-nrw/svws-ui'
    import { Head } from '@inertiajs/inertia-vue3'
    import { LoginFormData as Login } from '../../Interfaces/FormData'

    let props = defineProps({
        settings: {
            type: Object as PropType<Settings>,
            required: true,
        },
    })

    let data: Login = reactive({
        form: {
            email: '',
            password: '',
            remember: false,
        },
        processing: false,
        errors: [],
    })

    const getError = (column: string): string => data.errors[column][0]
    const hasErrors = (column: string): boolean => column in data.errors

    const requestPassword = (): void => Inertia.get(route('request_password'))

    const submit = (): void => {
        data.processing = true
        axios.post(route('login'), data.form)
            .then((): void => Inertia.get(route('dashboard')))
            .catch((error: any): AxiosError => data.errors = error.response.data.errors)
            .finally((): boolean => data.processing = false)
    }
</script>

<template>
    <div v-cloak>
        <Head>
            <title>Log in</title>
        </Head>

        <AuthLayout>
            <template #main>
                <div class="component">
                    <h2 class="headline-4">{{ settings.school_name }}</h2>
                    <div class="form-control">
                        <SvwsUiTextInput
                            v-model="data.form.email"
                            v-on:keyup.enter="submit"
                            :valid="!hasErrors('email')"
                            :disabled="data.processing"
                            type="email"
                            placeholder="E-Mail-Adresse"
                            required
                            autocomplete="email"
                        ></SvwsUiTextInput>

                        <span v-if="hasErrors('email')" class="error">
                            {{ getError('email')}}
                        </span>
                    </div>

                    <div class="form-control">
                        <SvwsUiTextInput
                            v-model="data.form.password"
                            v-on:keyup.enter="submit"
                            :valid="!hasErrors('password')"
                            :disabled="data.processing"
                            type="password"
                            placeholder="Passwort"
                            required
                        ></SvwsUiTextInput>

                        <span v-if="hasErrors('password')" class="error">
                            {{ getError('password') }}
                        </span>
                    </div>

                    <SvwsUiCheckbox v-model="data.form.remember" :disabled="data.processing">
                        Angemeldet bleiben
                    </SvwsUiCheckbox>

                    <div class="flex gap-6 justify-between">
                        <SvwsUiButton @click="submit()" :disabled="data.processing">Anmelden</SvwsUiButton>
                        <SvwsUiButton @click="requestPassword()">Passwort anfordern</SvwsUiButton>
                    </div>
                </div>
            </template>
        </AuthLayout>
    </div>
</template>

<style scoped>
    div.component {
        @apply rounded-lg shadow-lg p-8 flex flex-col gap-6 w-full max-w-lg bg-white
    }

    div.component > .form-control {
        @apply flex flex-col gap-0
    }

    div.component > .form-control > span.error {
        @apply text-red-500 text-sm mt-2
    }
</style>

