<script setup lang="ts">
    import { Head, useForm, usePage, Link } from '@inertiajs/inertia-vue3';
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
    import {Inertia} from "@inertiajs/inertia";
    import {computed} from "vue";
    import AuthLayout from "../../Layouts/AuthLayout.vue";

    defineProps({
        status: String,
    });

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const schoolName = computed(() => usePage().props.value.schoolName)

    const requestPassword = () => Inertia.get(route('request_password'))
    const submit = () => {
        form.transform(data => ({
            ...data,
            remember: form.remember ? 'on' : '',
        })).post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };
</script>

<template>
    <div v-cloak>
        <Head>
            <title>Log in</title>
        </Head>

        <AuthLayout>
            <template #sidebar>
                <div class="space-y-6">
                    <JetValidationErrors />
                    <h2 class="headline-4">{{ schoolName }}</h2>

                    <SvwsUiTextInput v-model="form.email" type="email" placeholder="E-Mail-Adresse" required :disabled="form.processing" v-on:keyup.enter="submit" />
                    <SvwsUiTextInput v-model="form.password" type="password" placeholder="Passwort" required :disabled="form.processing" v-on:keyup.enter="submit" />
                    <SvwsUiCheckbox v-model="form.remember" :disabled="form.processing">Angemeldet bleiben</SvwsUiCheckbox>
                    <div class="flex justify-between gap-6">
                        <SvwsUiButton @click="submit()" :disabled="form.processing">Anmelden</SvwsUiButton>
                        <SvwsUiButton @click="requestPassword()">Passwort anfordern</SvwsUiButton>
                    </div>
                </div>
            </template>
            <template #main></template>
        </AuthLayout>
    </div>
</template>

