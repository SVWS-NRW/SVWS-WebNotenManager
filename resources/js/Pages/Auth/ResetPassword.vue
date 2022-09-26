<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';


import AuthLayout from "../../Layouts/AuthLayout.vue"

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>


<template>
    <div>
        <Head>
            <title>Passwort eingeben</title>
        </Head>

        <AuthLayout>
            <template #sidebar>
                <div class="space-y-6">
                    <JetValidationErrors />

                            <h2 class="headline-4">Passwort eingeben</h2>

                            <SvwsUiTextInput v-model="form.email" type="email" placeholder="E-Mail-Adresse" required :disabled="form.processing" v-on:keyup.enter="submit" />
                            <SvwsUiTextInput v-model="form.password" type="password" placeholder="Passwort" required :disabled="form.processing" v-on:keyup.enter="submit" />
                            <SvwsUiTextInput v-model="form.password_confirmation" type="password" placeholder="Passwort bestätigen" required :disabled="form.processing" v-on:keyup.enter="submit" />
                            <SvwsUiButton @click="submit()" :disabled="form.processing">Speichern</SvwsUiButton>
                    </div>

            </template>
            <template #main>
            </template>
        </AuthLayout>
    </div>
</template>
