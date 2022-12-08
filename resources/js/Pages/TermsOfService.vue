<script setup>
    import { Head } from '@inertiajs/inertia-vue3'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'

    defineProps({
        terms: String,
    })

    const test = () => alert('TBD: TermsOfService.vue')
</script>

<template>

    <div class="font-sans text-gray-900 antialiased">
<button @click="test">qwe</button>
    </div>
</template>
