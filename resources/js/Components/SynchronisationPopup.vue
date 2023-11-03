<script setup lang="ts">
    import { ref } from 'vue';

    //TODO: make this work; it's a dummy so far
    let props = defineProps<{
        ref: string,
        show: boolean,
    }>()

    const message = ref('Warnung: es müssen die Einstellungen im zugehörigen SVWS-Server angepasste werden');
    const emit = defineEmits(['close']);

    const closeModal = () => {
        emit('close');
    };
</script>
<template>
    <div class="popup" @click.self="closeModal">
        <div class="popup-content">
            <div class="popup-header">
                <h2 class="popup-title">{{ message }}</h2>
                <button class="popup-close-button" @click.prevent="closeModal">X</button>
            </div>
            <article>
                <div class="popup-content-text">
                    Buttons Abrechen(close) / OK(generieren)
                </div>
            </article>
        </div>
    </div>
</template>

<style scoped>
    .content {
        @apply ui-px-6 ui-flex ui-flex-col ui-gap-12 ui-max-w-lg
    }

    .content>div {
        @apply ui-flex ui-flex-col ui-gap-5 ui-justify-start
    }

    .button {
        @apply ui-self-start
    }
</style>