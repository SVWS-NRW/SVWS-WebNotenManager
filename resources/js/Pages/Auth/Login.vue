<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';



const form = useForm({
    email: '',
    password: '',
    remember: false,
});

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
    <Head title="Log in" />

    <div class="min-h-screen w-full p-6 flex justify-center items-center">

            <form @submit.prevent="submit">
                <div class="flex flex-col gap-6">
                    <h1 class="svws-ui-headline-3">Login</h1>



                    <JetValidationErrors />

                    <SvwsUiTextInput v-model="form.email" type="email" required placeholder="E-Mail-Adresse" autocomplete="email" autofocus />
                    <SvwsUiTextInput v-model="form.password" type="password" required placeholder="Passwort" autocomplete="current-password" />

                    <div class="flex gap-6 justify-between items-center">
                        <SvwsUiButton @click="submit()" type="primary" :disabled="form.processing">Log in</SvwsUiButton>
                        <SvwsUiCheckbox v-model="form.remember">Angemeldet bleiben</SvwsUiCheckbox>
                    </div>
                </div>
            </form>
    </div>
</template>
