<script setup lang="ts">
    import { SvwsUiTextInput, SvwsUiCheckbox, SvwsUiButton } from '@svws-nrw/svws-ui'
    import { Head, Link } from '@inertiajs/inertia-vue3'
    import { Errors, Inertia } from '@inertiajs/inertia'
    import { reactive } from 'vue'
    import AuthLayout from '../../Layouts/AuthLayout.vue'
    import { PasswordRequestFormData as PasswordRequest } from '../../Interfaces/FormData'

    let data: PasswordRequest = reactive({
        form: {
            email: '',
            kuerzel: '',
            schulnummer: '',
        },
        processing: false,
        errors: {},
        successMessage: false,
    })

    const getError = (column: string): string => data.errors[column]
    const hasErrors = (column: string): boolean => column in data.errors

    const submit = (): void => Inertia.post(route('request_password'), data.form, {
        onSuccess: (): boolean => data.successMessage = true,
        onError: (error: Errors): Errors => data.errors = error,
        onFinish: (): boolean => data.processing = false
    })
</script>

<template>
    <div>
        <Head>
            <title>Passwort anfordern</title>
        </Head>

        <AuthLayout>
            <template #main>
                <div class="component">
                    <p class="status" v-if="data.successMessage">
                        <strong>Hinweis:</strong> <br>
                        Ihre Eingaben wurden empfangen. Wenn wir ein Konto haben, das Ihrer E-Mail-Adresse entspricht, erhalten Sie eine E-Mail mit einem Link, um Ihr Passwort zu setzen.
                    </p>

                    <h2 class="headline-4">Passwort anfordern</h2>

                    <div class="form-control">
                        <SvwsUiTextInput
                            v-model="data.form.schulnummer"
                            v-on:keyup.enter="submit"
                            :valid="!hasErrors('schulnummer')"
                            :disabled="data.processing"
                            type="text"
                            placeholder="Schulnummer"
                            required
                        ></SvwsUiTextInput>

                        <span v-if="hasErrors('schulnummer')" class="error">
                            {{ getError('schulnummer')}}
                        </span>
                    </div>

                    <div class="form-control">
                        <SvwsUiTextInput
                            v-model="data.form.kuerzel"
                            v-on:keyup.enter="submit"
                            :valid="!hasErrors('kuerzel')"
                            :disabled="data.processing"
                            type="text"
                            placeholder="Lehrkraftkürzel"
                            required
                        ></SvwsUiTextInput>

                        <span v-if="hasErrors('kuerzel')" class="error">
                            {{ getError('kuerzel')}}
                        </span>
                    </div>

                    <div class="form-control">
                        <SvwsUiTextInput
                            v-model="data.form.email"
                            v-on:keyup.enter="submit"
                            :valid="!hasErrors('email')"
                            :disabled="data.processing"
                            type="text"
                            placeholder="Lehrkraftkürzel"
                            required
                        ></SvwsUiTextInput>

                        <span v-if="hasErrors('email')" class="error">
                            {{ getError('email')}}
                        </span>
                    </div>

                    <SvwsUiButton @click="submit()" :disabled="data.processing" class="self-start">Link zusenden</SvwsUiButton>
                </div>
            </template>
        </AuthLayout>
    </div>
</template>

<style scoped>
    div.component {
        @apply rounded-lg shadow-lg p-8 flex flex-col gap-6 w-full max-w-lg bg-white
    }

    div.component .status {
        @apply font-medium text-sm text-green-600
    }

    div.component > .form-control {
        @apply flex flex-col gap-0
    }

    div.component > .form-control > span.error {
        @apply text-red-500 text-sm mt-2
    }
</style>