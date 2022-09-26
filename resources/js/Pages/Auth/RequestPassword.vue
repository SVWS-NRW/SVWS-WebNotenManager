<script setup lang="ts">
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
    import { Inertia } from '@inertiajs/inertia'
    import { reactive } from 'vue'
    import AuthLayout from "../../Layouts/AuthLayout.vue"

    const initialState = {
        email: '',
        kuerzel: '',
        schulnummer: '',
    };

    const form = reactive({
        ...initialState,
    });

    const state = reactive({
        successMessage: false,
    })

    const submit = () => Inertia.post(route('request_password'), form, {
        onSuccess: () => {
            Object.assign(form, initialState)
            state.successMessage = true
        }
    })
</script>

<template>
    <div>
        <Head>
            <title>Passwort anfordern</title>
        </Head>

        <AuthLayout>
            <template #sidebar>
                <div class="space-y-6">
                    <JetValidationErrors />
                    <p class="status" v-if="state.successMessage">
                        <strong>Hinweis:</strong> <br>
                        Ihre Eingaben wurden empfangen. Wenn wir ein Konto haben, das Ihrer E-Mail-Adresse entspricht, erhalten Sie eine E-Mail mit einem Link, um Ihr Passwort zu setzen.
                    </p>

                    <h2 class="headline-4">Passwort anfordern</h2>

                    <SvwsUiTextInput v-model="form.schulnummer" type="text" placeholder="Schulnummer" required :disabled="form.processing" v-on:keyup.enter="submit" />
                    <SvwsUiTextInput v-model="form.email" type="email" placeholder="E-Mail-Adresse" required :disabled="form.processing" v-on:keyup.enter="submit" />
                    <SvwsUiTextInput v-model="form.kuerzel" type="text" placeholder="Lehrkraftkürzel" required :disabled="form.processing" v-on:keyup.enter="submit" />
                    <SvwsUiButton @click="submit()" :disabled="form.processing">Link zusenden</SvwsUiButton>
                </div>

            </template>

            <template #main></template>
        </AuthLayout>
    </div>
</template>

<style scoped>
    /*#login {*/
    /*    @apply flex h-full w-screen flex-col justify-between px-16 max-w-[581px]*/
    /*}*/

    /*#login > header {*/
    /*    @apply space-y-16 pt-16*/
    /*}*/

    /*#login > header > #logo {*/
    /*    @apply mb-12 flex flex-row items-center gap-4 w-full*/
    /*}*/

    /*#login > header > #logo > .headline {*/
    /*    @apply flex flex-col*/
    /*}*/

    /*#login > header > #logo > .headline > h1 {*/
    /*    @apply text-3xl font-bold*/
    /*}*/

    /*#login > header > #logo > .headline > span {*/
    /*    @apply font-medium*/
    /*}*/

    /*#login > header > #logo > svg {*/
    /*    @apply h-12 w-12*/
    /*}*/

    /*#login > header > .form {*/
    /*    @apply space-y-6*/
    /*}*/

    /*#login > header > .form > p.status {*/
    /*    @apply font-medium text-sm text-green-600*/
    /*}*/


</style>
